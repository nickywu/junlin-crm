<?php

namespace app\model\product;

use core\base\BaseModel;
use think\model\concern\SoftDelete;
use app\model\system\User;

class Product extends BaseModel
{

    use SoftDelete;

    //开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    //自动写入时间戳字段
    protected $createTime = 'create_time';


    //软删除字段
    protected $deleteTime = 'delete_time';

    //软删除默认值
    protected $defaultSoftDelete = 0;


    //定义用户相对关联
    public function user()
    {
        return $this->belongsTo(User::class, 'create_user_id', 'id')->bind(['realname']);
    }

    //产品类别
    public function getCategoryTextAttr($value, $data)
    {
        return get_dict_map('product_category', $data['category']);
    }

    //产品单位
    public function getUnitTextAttr($value, $data)
    {
         return get_dict_map('product_unit', $data['unit']);
    }


    //关键词
    public function searchKeyAttr($query, $value)
    {
        $query->whereLike('name|code', $value);
    }

    //产品类别
    public function searchCategoryAttr($query, $value)
    {
        $query->where('category', $value);
    }

    //添加时间
    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereTime('create_time', 'between', between_time($value));
    }


    //创建人
    public function searchCreateUserIdAttr($query, $value)
    {
        $query->where('create_user_id', $value);
    }

    //产品单位
    public function searchUnitAttr($query, $value)
    {
        $query->where('unit', $value);
    }
}
