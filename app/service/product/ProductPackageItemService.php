<?php

namespace app\service\product;

use core\base\BaseService;
use app\model\product\ProductPackageItem;

class ProductPackageItemService extends BaseService
{
    /**
     * 初始化套餐子业务模板服务
     *
     * @param ProductPackageItem $model 套餐子业务模板模型
     * @return void
     */
    public function __construct(ProductPackageItem $model)
    {
        $this->model = $model;
    }

    /**
     * 获取套餐子业务模板列表
     *
     * @return mixed
     */
    public function getList()
    {
        return $this->model
            ->search()
            ->with(['packageProduct', 'childProduct'])
            ->append(['core_text'])
            ->order('sort', 'asc')
            ->order('id', 'desc')
            ->paginate();
    }

    /**
     * 保存套餐子业务模板
     *
     * @param array $data 模板数据
     * @return int
     */
    public function save(array $data)
    {
        $data['create_user_id'] = request()->uid();
        return $this->model->storeBy($data);
    }

    /**
     * 更新套餐子业务模板
     *
     * @param int $id 模板ID
     * @param array $data 模板数据
     * @return bool
     */
    public function update($id, array $data)
    {
        return $this->model->updateBy($id, $data);
    }

    /**
     * 获取套餐子业务模板编辑数据
     *
     * @param int $id 模板ID
     * @return ProductPackageItem
     */
    public function edit($id)
    {
        $data = $this->model->findOrFail($id);
        $data->package_product = $data->packageProduct()->field('id,name')->find();
        $data->child_product = $data->childProduct()->field('id,name')->find();
        return $data;
    }

    /**
     * 删除套餐子业务模板
     *
     * @param array $ids 模板ID列表
     * @return bool
     */
    public function delete(array $ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
}
