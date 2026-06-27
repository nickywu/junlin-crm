<?php

namespace app\service\contract;

use core\base\BaseService;
use app\model\contract\Contract;
use app\model\system\User;
use app\model\contract\ContractProduct;
use app\model\customer\Customer;
use app\model\business\Business;
use app\model\contacts\Contacts;
use app\model\product\Product;
use app\model\product\ProductPackageItem;
use app\model\product\ProductStep;
use app\model\contract\ServicePeriod;
use app\model\contract\WorkOrder;
use app\model\contract\WorkOrderNode;
use core\exception\FailedException;
use core\facade\Util;

class ContractService extends BaseService
{


    public function __construct(Contract $model)
    {
        $this->model = $model;
    }



    /**
     * 查询列表
     *
     * @return array
     */
    public function getList()
    {
        $data = $this->model
            ->dataRange('owner_user_id')
            ->search()
            ->order('id', 'desc')
            ->append(['last_time_text', 'status_text'])
            ->with(['ownerUser', 'orderUser', 'customer', 'business', 'contacts', 'salesManager', 'workManager'])
            ->paginate();
        return $data;
    }


    /**
     * 获取合同产品列表
     *
     * @param int $id
     * @return array
     */
    public function getProduct($id)
    {
        return ContractProduct::where('contract_id', $id)
            ->with(['product', 'ownerUser', 'workManager'])
            ->append(['finish_status_text', 'core_text'])
            ->order('sort', 'asc')
            ->order('id', 'asc')
            ->paginate();
    }

    /**
     * 保存
     * @param array $data
     * @return bool
     */
    public function save(array $data)
    {
        $data['create_user_id'] = request()->uid();
        $data['owner_user_id'] = request()->uid();
        $data['order_no'] = $this->createOrderNo();
        $product = $this->normalizeContractProducts($data['product'] ?? [], $data);
        $this->startTrans();
        try {
            $contract_id = $this->model->storeBy($data);
            if (!empty($product) && $contract_id) {
                $contract = $this->model->find($contract_id);
                // 批量增加关联数据
                foreach ($product as $key => $value) {
                    //过滤掉id
                    if (!empty($value['id'])) {
                        unset($product[$key]['id']);
                    }
                }
                $contract->contractProduct()->saveAll($product);
                $this->generateWorkflow($contract_id);
            }
            if ($contract_id) {
                action_log('contract', $contract_id, '新增', '新增合同『' . $data['name'] . '』');
            }
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }
        return true;
    }

    /**
     * 更换负责人
     *
     * @param  array  $id
     * @return bool
     */
    public function changeOwnerUser(array $contract_id, $owner_user_id)
    {
        $authIds = $this->model->getUserIdsByPermissions();
        $data['owner_user_id'] = $owner_user_id;
        $contractData = $this->model->whereIn('id', $contract_id)->select()->toArray();
        $realname = User::getRealNameById($owner_user_id);
        //错误信息
        $errorInfo = [];
        foreach ($contractData as $key => $value) {
            // 如果已经是该负责人，跳过
            if ($value['owner_user_id'] == $owner_user_id) {
                continue;
            }
            //权限判断
            if (!empty($authIds) && !in_array($value['owner_user_id'], $authIds)) {
                $errorInfo[] = '合同『' . $value['name'] . '』更换负责人失败，没有权限；';
                continue;
            }
            $result = $this->model->where('id', $value['id'])->update($data);
            if (!$result) {
                $errorInfo[] = '客户『' . $value['name'] . '』更换负责人失败，数据出错；';
                continue;
            }
            action_log('contract', $value['id'], '更换负责人', '合同『' . $value['name'] . '』更换负责人为' . $realname);
        }
        if (empty($errorInfo)) {
            return true;
        } else {
            $this->error = $errorInfo;
            return false;
        }
    }

    /**
     * 更新
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $this->startTrans();
        try {
            $contractData = $this->model->findOrFail($id)->toArray();
            $changed = Util::getChangedFields($contractData, $data, ['product']);
            [$result, $message] = $this->model->checkDataAuth($contractData['owner_user_id'], $contractData['name']);
            if (!$result) throw new FailedException($message);
            $product = $this->normalizeContractProducts($data['product'] ?? [], array_merge($contractData, $data));
            $result = $this->model->updateBy($id, $data);
            Product::syncRelatedRecords(ContractProduct::class, 'contract_id', $id, $product);
            $this->cleanInvalidWorkflow($id);
            $this->generateWorkflow($id);
            if ($result) {
                $logContent = '更新合同『' . ($data['name'] ?? $contractData['name']) . '』，变更字段：';
                $logContent .=  Util::formatChangedFields($changed);
                // 写入日志
                action_log('business', $id, '更新', $logContent);
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }









    /**
     * 获取编辑的数据
     *
     * @param  int  $id
     * @return array
     */
    public function edit($id)
    {
        $data = $this->model->findOrFail($id);
        $data->customer = Customer::field('id,name')->find($data->customer_id);
        $data->business = Business::field('id,name')->find($data->business_id);
        $data->contacts = Contacts::field('id,name')->find($data->contacts_id);
        $data->product  = ContractProduct::where('contract_id', $id)->with(['product', 'ownerUser', 'workManager'])->append(['finish_status_text', 'core_text'])->select();
        return $data;
    }


    /**
     * 生成合同子业务的服务周期和工单
     *
     * @param int $contractId 合同ID
     * @return bool
     */
    public function generateWorkflow($contractId): bool
    {
        $items = ContractProduct::where('contract_id', $contractId)->order('sort', 'asc')->order('id', 'asc')->select();
        foreach ($items as $item) {
            if (!$item->period_generated && $this->shouldCreatePeriod($item)) {
                $this->createPeriodsForProduct($item);
            }
            if (!$item->work_order_generated) {
                $this->createWorkOrdersForProduct($item);
            }
        }
        return true;
    }


    /**
     * 按套餐模板展开并规范化合同子业务数据
     *
     * @param array $items 前端提交的产品/子业务列表
     * @param array $contractData 合同数据
     * @return array
     */
    private function normalizeContractProducts(array $items, array $contractData): array
    {
        $expanded = $this->expandPackageItems($items);
        $result = [];
        foreach ($expanded as $index => $item) {
            $result[] = $this->buildContractProductItem($item, $contractData, $index);
        }
        return $result;
    }


    /**
     * 将套餐产品展开为子业务模板明细
     *
     * @param array $items 前端选择的产品列表
     * @return array
     */
    private function expandPackageItems(array $items): array
    {
        $result = [];
        foreach ($items as $item) {
            if (!empty($item['id'])) {
                // 已存在的合同子业务必须原样保留，避免编辑合同后重复展开套餐。
                $result[] = $item;
                continue;
            }
            $templates = ProductPackageItem::where('package_product_id', $item['product_id'] ?? 0)
                ->where('status', 1)
                ->order('sort', 'asc')
                ->order('id', 'asc')
                ->select()
                ->toArray();
            if (empty($templates)) {
                $result[] = $item;
                continue;
            }
            foreach ($templates as $template) {
                $result[] = array_merge($item, [
                    'product_id' => $template['child_product_id'] ?: ($item['product_id'] ?? 0),
                    'product_name' => $template['name'] ?: ($item['name'] ?? ''),
                    'name' => $template['name'] ?: ($item['name'] ?? ''),
                    'service_type' => $template['service_type'] ?: ($item['service_type'] ?? ''),
                    'number' => $template['default_number'] ?: 1,
                    'share_amount' => $template['default_share_amount'],
                    'internal_cost' => $template['default_internal_cost'],
                    'fixed_cost' => $template['default_fixed_cost'],
                    'commission_cost' => $template['default_commission_cost'],
                    'other_cost' => $template['default_other_cost'],
                    'sales_commission' => $template['default_sales_commission'],
                    'sales_manager_commission' => $template['default_sales_manager_commission'],
                    'work_manager_bonus' => $template['default_work_manager_bonus'],
                    'worker_commission' => $template['default_worker_commission'],
                    'period_unit' => $template['period_unit'],
                    'period_total' => $template['period_total'],
                    'display_step' => $template['flow_template'],
                    'is_core' => $template['is_core'],
                    'category' => $template['category'] ?: ($item['category'] ?? 0),
                    'unit' => $template['unit'] ?: ($item['unit'] ?? 0),
                    'sort' => $template['sort'],
                ]);
            }
        }
        return $result;
    }


    /**
     * 构建合同子业务实例字段
     *
     * @param array $item 子业务原始数据
     * @param array $contractData 合同数据
     * @param int $index 当前序号
     * @return array
     */
    private function buildContractProductItem(array $item, array $contractData, int $index): array
    {
        $product = Product::find($item['product_id'] ?? 0);
        $number = max(1, intval($item['number'] ?? 1));
        $price = $this->decimal($item['price'] ?? ($product['price'] ?? 0));
        $salePrice = $this->decimal($item['sale_price'] ?? $price);
        $discount = $this->decimal($item['discount'] ?? 0);
        $discountRate = $discount > 0 ? $discount / 100 : 1;
        $totalPrice = $this->decimal($item['total_price'] ?? ($salePrice * $number * $discountRate));
        $shareAmount = $this->decimal($item['share_amount'] ?? 0);
        $shareAmount = $shareAmount > 0 ? $shareAmount : $totalPrice;
        $internalCost = $this->decimal($item['internal_cost'] ?? ($product['internal_cost'] ?? 0));
        $fixedCost = $this->decimal($item['fixed_cost'] ?? ($product['fixed_cost'] ?? 0));
        $commissionCost = $this->decimal($item['commission_cost'] ?? ($product['commission_cost'] ?? 0));
        $otherCost = $this->decimal($item['other_cost'] ?? ($product['other_cost'] ?? 0));
        $actualCost = $this->decimal($item['actual_cost'] ?? ($internalCost + $fixedCost + $commissionCost + $otherCost));
        $salesCommission = $this->decimal($item['sales_commission'] ?? ($product['sales_commission'] ?? 0));
        $salesManagerCommission = $this->decimal($item['sales_manager_commission'] ?? ($product['sales_manager_commission'] ?? 0));
        $workManagerBonus = $this->decimal($item['work_manager_bonus'] ?? ($product['work_order_manager_bonus'] ?? 0));
        $workerCommission = $this->decimal($item['worker_commission'] ?? 0);
        $grossProfit = $this->decimal($item['gross_profit'] ?? ($shareAmount - $actualCost));
        $backendProfit = $this->decimal($item['backend_profit'] ?? ($grossProfit - $salesCommission - $salesManagerCommission - $workManagerBonus - $workerCommission));
        $serviceType = $item['service_type'] ?? ($product['service_type'] ?? 'single');
        $periodTotal = intval($item['period_total'] ?? ($product['service_period'] ?? 0));
        $periodUnit = $item['period_unit'] ?? ($serviceType === 'long_term' ? 'month' : 'none');

        return [
            'id' => $item['id'] ?? null,
            'product_id' => intval($item['product_id'] ?? 0),
            'product_name' => $item['product_name'] ?? ($item['name'] ?? ($product['name'] ?? '')),
            'service_type' => $serviceType,
            'price' => $price,
            'sale_price' => $salePrice,
            'number' => $number,
            'discount' => $discount,
            'unit' => intval($item['unit'] ?? ($product['unit'] ?? 0)),
            'total_price' => $totalPrice,
            'share_amount' => $shareAmount,
            'internal_cost' => $internalCost,
            'fixed_cost' => $fixedCost,
            'commission_cost' => $commissionCost,
            'other_cost' => $otherCost,
            'actual_cost' => $actualCost,
            'gross_profit' => $grossProfit,
            'backend_profit' => $backendProfit,
            'sales_commission' => $salesCommission,
            'sales_manager_commission' => $salesManagerCommission,
            'work_manager_bonus' => $workManagerBonus,
            'worker_commission' => $workerCommission,
            'owner_user_id' => intval($item['owner_user_id'] ?? ($contractData['owner_user_id'] ?? request()->uid())),
            'work_manager_id' => intval($item['work_manager_id'] ?? ($contractData['work_manager_id'] ?? 0)),
            'flow_node' => $item['flow_node'] ?? '',
            'finish_status' => intval($item['finish_status'] ?? 0),
            'category' => intval($item['category'] ?? ($product['category'] ?? 0)),
            'display_step' => $item['display_step'] ?? '',
            'is_core' => intval($item['is_core'] ?? 0),
            'period_unit' => $periodUnit ?: 'none',
            'period_total' => $periodTotal,
            'period_generated' => intval($item['period_generated'] ?? 0),
            'work_order_generated' => intval($item['work_order_generated'] ?? 0),
            'service_start_time' => $this->timeValue($item['service_start_time'] ?? ($contractData['start_time'] ?? 0)),
            'service_end_time' => $this->timeValue($item['service_end_time'] ?? ($contractData['end_time'] ?? 0)),
            'finish_time' => $this->timeValue($item['finish_time'] ?? 0),
            'sort' => intval($item['sort'] ?? $index),
            'remark' => $item['remark'] ?? '',
        ];
    }


    /**
     * 判断子业务是否需要生成服务周期
     *
     * @param ContractProduct $item 合同子业务
     * @return bool
     */
    private function shouldCreatePeriod(ContractProduct $item): bool
    {
        return intval($item->period_total) > 0 || in_array($item->service_type, ['long_term', 'periodic', 'stage']);
    }


    /**
     * 为子业务创建服务周期
     *
     * @param ContractProduct $item 合同子业务
     * @return void
     */
    private function createPeriodsForProduct(ContractProduct $item): void
    {
        $total = intval($item->period_total);
        if ($total <= 0 && $item->service_type === 'long_term') {
            $total = 1;
        }
        if ($total <= 0) {
            return;
        }
        $rows = [];
        $startTime = $item->service_start_time ?: time();
        $shareAmount = $this->divideAmount($item->share_amount, $total);
        $actualCost = $this->divideAmount($item->actual_cost, $total);
        $internalCost = $this->divideAmount($item->internal_cost, $total);
        $workerCommission = $this->divideAmount($item->worker_commission, $total);
        for ($i = 1; $i <= $total; $i++) {
            [$periodStart, $periodEnd] = $this->buildPeriodRange($startTime, $item->period_unit, $i);
            $rows[] = [
                'contract_id' => $item->contract_id,
                'contract_product_id' => $item->id,
                'period_no' => $i,
                'period_name' => $this->buildPeriodName($item, $i),
                'period_unit' => $item->period_unit ?: 'month',
                'period_start_time' => $periodStart,
                'period_end_time' => $periodEnd,
                'owner_user_id' => $item->owner_user_id,
                'status' => 0,
                'share_amount' => $shareAmount,
                'internal_cost' => $internalCost,
                'actual_cost' => $actualCost,
                'worker_commission' => $workerCommission,
                'create_user_id' => request()->uid(),
            ];
        }
        (new ServicePeriod())->insertAllBy($rows);
        ContractProduct::where('id', $item->id)->update(['period_generated' => 1]);
    }


    /**
     * 为子业务创建工单
     *
     * @param ContractProduct $item 合同子业务
     * @return void
     */
    private function createWorkOrdersForProduct(ContractProduct $item): void
    {
        $periods = ServicePeriod::where('contract_product_id', $item->id)->order('period_no', 'asc')->select();
        if (!$periods->isEmpty()) {
            foreach ($periods as $period) {
                $workOrderId = $this->createWorkOrder([
                    'service_period_id' => $period->id,
                    'title' => $item->product_name . '-' . $period->period_name,
                    'plan_start_time' => $period->period_start_time,
                    'plan_end_time' => $period->period_end_time,
                ], $item);
                $this->createWorkOrderNodes($workOrderId, $item);
            }
        } else {
            $workOrderId = $this->createWorkOrder([
                'service_period_id' => 0,
                'title' => $item->product_name,
                'plan_start_time' => $item->service_start_time,
                'plan_end_time' => $item->service_end_time,
            ], $item);
            $this->createWorkOrderNodes($workOrderId, $item);
        }
        ContractProduct::where('id', $item->id)->update(['work_order_generated' => 1]);
    }


    /**
     * 创建单个工单
     *
     * @param array $data 工单覆盖字段
     * @param ContractProduct $item 合同子业务
     * @return int
     */
    private function createWorkOrder(array $data, ContractProduct $item): int
    {
        return (new WorkOrder())->createBy(array_merge([
            'work_order_no' => $this->createWorkOrderNo(),
            'contract_id' => $item->contract_id,
            'contract_product_id' => $item->id,
            'work_type' => $item->service_type,
            'status' => 0,
            'priority' => 1,
            'owner_user_id' => $item->owner_user_id,
            'assist_user_id' => 0,
            'work_manager_id' => $item->work_manager_id,
            'progress' => 0,
            'current_node_id' => 0,
            'create_user_id' => request()->uid(),
        ], $data));
    }


    /**
     * 创建工单流程节点
     *
     * @param int $workOrderId 工单ID
     * @param ContractProduct $item 合同子业务
     * @return void
     */
    private function createWorkOrderNodes(int $workOrderId, ContractProduct $item): void
    {
        $steps = ProductStep::where('product_id', $item->product_id)->order('sort', 'asc')->order('id', 'asc')->select()->toArray();
        if (empty($steps) && !empty($item->display_step)) {
            // 套餐模板允许用逗号维护轻量流程，缺少产品步骤时作为兜底节点来源。
            foreach (preg_split('/[,，]/u', $item->display_step) as $index => $name) {
                if (trim($name) !== '') {
                    $steps[] = ['name' => trim($name), 'sort' => $index + 1];
                }
            }
        }
        if (empty($steps)) {
            $steps[] = ['name' => '办理完成', 'sort' => 1];
        }
        $firstNodeId = 0;
        foreach ($steps as $index => $step) {
            $nodeId = (new WorkOrderNode())->createBy([
                'work_order_id' => $workOrderId,
                'name' => $step['name'] ?? '办理节点',
                'node_key' => 'node_' . ($index + 1),
                'sort' => intval($step['sort'] ?? ($index + 1)),
                'status' => $index === 0 ? 1 : 0,
                'owner_user_id' => $item->owner_user_id,
                'create_user_id' => request()->uid(),
            ]);
            if ($index === 0) {
                $firstNodeId = $nodeId;
            }
        }
        if ($firstNodeId) {
            WorkOrder::where('id', $workOrderId)->update(['current_node_id' => $firstNodeId, 'status' => 1]);
        }
    }


    /**
     * 清理已删除子业务遗留的执行记录
     *
     * @param int $contractId 合同ID
     * @return void
     */
    private function cleanInvalidWorkflow(int $contractId): void
    {
        $aliveIds = ContractProduct::where('contract_id', $contractId)->column('id');
        $deleteWorkOrderIds = WorkOrder::where('contract_id', $contractId)->whereNotIn('contract_product_id', $aliveIds ?: [0])->column('id');
        if (!empty($deleteWorkOrderIds)) {
            WorkOrderNode::whereIn('work_order_id', $deleteWorkOrderIds)->delete();
            WorkOrder::whereIn('id', $deleteWorkOrderIds)->delete();
        }
        ServicePeriod::where('contract_id', $contractId)->whereNotIn('contract_product_id', $aliveIds ?: [0])->delete();
    }


    /**
     * 生成周期起止时间
     *
     * @param int $startTime 合同服务开始时间
     * @param string $periodUnit 周期单位
     * @param int $periodNo 周期序号
     * @return array
     */
    private function buildPeriodRange(int $startTime, string $periodUnit, int $periodNo): array
    {
        $unit = $periodUnit === 'year' ? 'year' : 'month';
        $periodStart = strtotime('+' . ($periodNo - 1) . ' ' . $unit, $startTime);
        $periodEnd = strtotime('+1 ' . $unit, $periodStart) - 1;
        return [$periodStart, $periodEnd];
    }


    /**
     * 生成周期名称
     *
     * @param ContractProduct $item 合同子业务
     * @param int $periodNo 周期序号
     * @return string
     */
    private function buildPeriodName(ContractProduct $item, int $periodNo): string
    {
        $unitText = $item->period_unit === 'year' ? '年' : ($item->period_unit === 'stage' ? '阶段' : '月');
        return '第' . $periodNo . $unitText;
    }


    /**
     * 生成工单编号
     *
     * @return string
     */
    private function createWorkOrderNo(): string
    {
        return 'W' . date('ymdHis') . mt_rand(100, 999);
    }


    /**
     * 金额格式化
     *
     * @param mixed $value 原始值
     * @return float
     */
    private function decimal($value): float
    {
        return round(floatval($value), 2);
    }


    /**
     * 平均分摊金额
     *
     * @param mixed $amount 总金额
     * @param int $total 分摊数量
     * @return float
     */
    private function divideAmount($amount, int $total): float
    {
        return $total > 0 ? round(floatval($amount) / $total, 2) : 0;
    }


    /**
     * 将日期或时间戳统一转为时间戳
     *
     * @param mixed $value 日期字符串或时间戳
     * @return int
     */
    private function timeValue($value): int
    {
        if (empty($value)) {
            return 0;
        }
        if (is_numeric($value)) {
            return intval($value);
        }
        return strtotime($value) ?: 0;
    }


    /**
     * 获取详情的数据
     *
     * @param  int  $id
     * @return array
     */
    public function read($id)
    {
        $data = $this->model
            ->with(['orderUser', 'customer', 'business', 'contacts', 'salesManager', 'workManager'])
            ->findOrFail($id)
            ->append(['last_time_text', 'status_text']);
        $data->create_user_name = User::getRealNameById($data->create_user_id);
        $data->owner_user_name = User::getRealNameById($data->owner_user_id);
        return $data;
    }


    /**
     * 删除
     * @param  $id  id主键
     * @return bool
     */
    public function delete($contract_id)
    {
        $authIds = $this->model->getUserIdsByPermissions();
        //错误信息
        $errorInfo = [];
        foreach ($contract_id as $v) {
            $contract = $this->model->find($v);
            if (!$contract) {
                $errorInfo[] = '合同ID『' . $v . '』数据不存在；';
                continue;
            }
            //权限判断
            if (!empty($authIds) && !in_array($contract->owner_user_id, $authIds)) {
                $errorInfo[] = '合同『' . $contract->getAttr('name') . '』删除失败，没有权限删除该合同；';
                continue;
            }
            $result = $this->model->deleteBy($v);
            if ($result) {
                action_log('contract', $v, '删除', '删除合同『' . $contract->getAttr('name') . '』');
            }
        }

        if (empty($errorInfo)) {
            return true;
        } else {
            $this->error = $errorInfo;
            return false;
        }
    }


    /**
     * 创建合同编号
     * @return  string     
     */
    protected function createOrderNo()
    {
        $prefix = 'H';
        $tmpTodayStr = date('ymd');
        $todayMaxTime = strtotime(date('Ymd')) + 86399;
        $mapTrade[] = ["create_time", '<', $todayMaxTime];
        $todayMaxOrder = $this->model->withTrashed()->where($mapTrade)->field('order_no,create_time')->order('id desc')->find();
        if ($todayMaxOrder != null && $todayMaxOrder->getData('create_time') < $todayMaxTime) {
            //证明还是今天
            $tmpDateStr = $prefix . $tmpTodayStr;
            $tmpMaxId = str_replace($tmpDateStr, '', $todayMaxOrder['order_no']);
            $tmpMaxId = intval($tmpMaxId) + 1;
        } else {
            $tmpMaxId = 1;
        }
        $newTradeId = $tmpMaxId;
        $newTradeId = str_pad($newTradeId, 3, '0', STR_PAD_LEFT);
        $buildOrderId = $prefix . $tmpTodayStr . $newTradeId;
        return $buildOrderId;
    }
}
