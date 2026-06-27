<?php

namespace app\service\product;

use core\base\BaseService;
use app\model\product\ProductStep;

class ProductStepService extends BaseService
{
    public function __construct(ProductStep $model)
    {
        $this->model = $model;
    }

    /**
     * 获取某产品的步骤列表
     * @return \think\Paginator
     */
    public function getList()
    {
        $data = $this->model->search()
            ->order('sort', 'asc')
            ->order('id', 'asc')
            ->paginate();
        return $data;
    }

    /**
     * 新增步骤
     * @param array $data 步骤数据
     * @return int 新步骤ID
     */
    public function save(array $data)
    {
        $data['create_user_id'] = request()->uid();
        $stepId = $this->model->storeBy($data);
        return $stepId;
    }

    /**
     * 更新步骤
     * @param int $id 步骤ID
     * @param array $data 步骤数据
     * @return bool
     */
    public function update($id, array $data)
    {
        $result = $this->model->updateBy($id, $data);
        return $result;
    }

    /**
     * 获取编辑数据（含关联产品信息，用于前端回显）
     * @param int $id 步骤ID
     * @return array
     */
    public function edit($id)
    {
        $data = $this->model->findOrFail($id);
        // 查询关联产品信息，供前端 TableSelect 回显
        $product = \app\model\product\Product::field('id,name,category,price,code')
            ->where('id', $data['product_id'])
            ->find();
        $data['product'] = $product ?? [];
        return $data;
    }

    /**
     * 批量删除步骤
     * @param array $ids 步骤ID数组
     * @return bool
     */
    public function delete($ids)
    {
        $errorInfo = [];
        foreach ($ids as $v) {
            $step = $this->model->find($v);
            if (!$step) {
                $errorInfo[] = '步骤ID『' . $v . '』数据不存在；';
                continue;
            }
            $this->model->deleteBy($v);
        }
        if (empty($errorInfo)) {
            return true;
        } else {
            $this->error = $errorInfo;
            return false;
        }
    }
}
