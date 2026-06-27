<?php

namespace app\model\product;

use core\base\BaseModel;
use think\model\concern\SoftDelete;
use app\model\product\Product;

class ProductStep extends BaseModel
{
    use SoftDelete;

    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 自动写入时间戳字段
    protected $createTime = 'create_time';

    // 软删除字段
    protected $deleteTime = 'delete_time';

    // 软删除默认值
    protected $defaultSoftDelete = 0;

    /**
     * 关联产品
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * 关键词搜索器，按步骤名称模糊搜索
     * @param \think\db\Query $query
     * @param string $value 搜索关键词
     */
    public function searchKeyAttr($query, $value)
    {
        $query->whereLike('name', $value);
    }

    /**
     * 按产品ID过滤
     * @param \think\db\Query $query
     * @param int $value 产品ID
     */
    public function searchProductIdAttr($query, $value)
    {
        $query->where('product_id', $value);
    }
}
