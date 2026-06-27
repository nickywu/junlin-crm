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
        return ContractProduct::where('contract_id', $id)->with(['product'])->paginate();
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
        $product = $data['product'] ?? [];
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
            $product = $data['product'] ?? [];
            $result = $this->model->updateBy($id, $data);
            Product::syncRelatedRecords(ContractProduct::class, 'contract_id', $id, $product);
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
        $data->product  = ContractProduct::where('contract_id', $id)->with(['product'])->select();
        return $data;
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
