<?php

namespace app\service\collect;

use core\base\BaseService;
use app\model\collect\LongCollect;
use app\model\contract\ContractProduct;
use app\model\product\Product;
use core\exception\FailedException;
use core\facade\Util;

class LongCollectService extends BaseService
{
    public function __construct(LongCollect $model)
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
            ->append(['pay_type_text'])
            ->with(['contract', 'customer', 'createUser'])
            ->paginate();
        return $data;
    }

    /**
     * 获取长期服务类型的合同产品列表（供下拉选择用）
     *
     * @param array $params 搜索参数（支持contract_id过滤）
     * @return array
     */
    public function getContractProducts($params = [])
    {
        $query = ContractProduct::alias('cp')
            ->join('speed_product p', 'cp.product_id = p.id')
            ->where('p.service_type', 'long_term')
            ->field('cp.*, p.name as product_name, p.service_type');

        if (!empty($params['contract_id'])) {
            $query->where('cp.contract_id', $params['contract_id']);
        }

        if (!empty($params['key'])) {
            $query->whereLike('p.name', $params['key']);
        }

        return $query->paginate();
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
        $result = $this->model->storeBy($data);
        if ($result) {
            action_log('collect_long', $result, '新增', '新增长期服务收款');
        }
        return $result ? true : false;
    }

    /**
     * 更新
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $collectData = $this->model->findOrFail($id)->toArray();
        $changed = Util::getChangedFields($collectData, $data);
        [$result, $message] = $this->model->checkDataAuth($collectData['owner_user_id'], '收款记录');
        if (!$result) throw new FailedException($message);
        $result = $this->model->updateBy($id, $data);
        if ($result) {
            $logContent = '更新长期服务收款，变更字段：';
            $logContent .= Util::formatChangedFields($changed);
            action_log('collect_long', $id, '更新', $logContent);
        }
        return true;
    }

    /**
     * 获取编辑的数据
     *
     * @param int $id
     * @return array
     */
    public function edit($id)
    {
        $data = $this->model->findOrFail($id);
        // 加载合同产品信息供前端回显
        $contractProduct = ContractProduct::alias('cp')
            ->join('speed_product p', 'cp.product_id = p.id')
            ->field('cp.*, p.name as product_name')
            ->where('cp.id', $data['contract_product_id'])
            ->find();
        $data['contract_product'] = $contractProduct ?? [];
        return $data;
    }

    /**
     * 删除
     * @param $id id主键
     * @return bool
     */
    public function delete($collect_id)
    {
        $authIds = $this->model->getUserIdsByPermissions();
        $errorInfo = [];
        foreach ($collect_id as $v) {
            $collect = $this->model->find($v);
            if (!$collect) {
                $errorInfo[] = '收款记录ID『' . $v . '』数据不存在；';
                continue;
            }
            if (!empty($authIds) && !in_array($collect->owner_user_id, $authIds)) {
                $errorInfo[] = '收款记录删除失败，没有权限；';
                continue;
            }
            $result = $this->model->deleteBy($v);
            if ($result) {
                action_log('collect_long', $v, '删除', '删除长期服务收款记录');
            }
        }
        if (empty($errorInfo)) {
            return true;
        } else {
            $this->error = $errorInfo;
            return false;
        }
    }
}
