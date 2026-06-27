<?php

namespace app\model\product;

use core\base\BaseModel;
use think\model\concern\SoftDelete;

class ProductPackageItem extends BaseModel
{
    use SoftDelete;

    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_time';

    protected $deleteTime = 'delete_time';

    protected $defaultSoftDelete = 0;

    /**
     * 关联套餐产品
     *
     * @return \think\model\relation\BelongsTo
     */
    public function packageProduct()
    {
        return $this->belongsTo(Product::class, 'package_product_id', 'id')->bind(['package_product_name' => 'name']);
    }

    /**
     * 关联子业务产品
     *
     * @return \think\model\relation\BelongsTo
     */
    public function childProduct()
    {
        return $this->belongsTo(Product::class, 'child_product_id', 'id')->bind(['child_product_name' => 'name']);
    }

    /**
     * 搜索套餐产品
     *
     * @param mixed $query 查询对象
     * @param mixed $value 套餐产品ID
     * @return void
     */
    public function searchPackageProductIdAttr($query, $value)
    {
        $query->where('package_product_id', $value);
    }

    /**
     * 搜索服务类型
     *
     * @param mixed $query 查询对象
     * @param mixed $value 服务类型
     * @return void
     */
    public function searchServiceTypeAttr($query, $value)
    {
        $query->where('service_type', $value);
    }

    /**
     * 搜索状态
     *
     * @param mixed $query 查询对象
     * @param mixed $value 状态
     * @return void
     */
    public function searchStatusAttr($query, $value)
    {
        $query->where('status', $value);
    }

    /**
     * 获取是否核心业务文本
     *
     * @param mixed $value 原始值
     * @param array $data 当前数据
     * @return string
     */
    public function getCoreTextAttr($value, $data)
    {
        return !empty($data['is_core']) ? '是' : '否';
    }
}
