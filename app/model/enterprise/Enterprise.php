<?php

namespace app\model\enterprise;

use core\base\BaseModel;
use think\model\concern\SoftDelete;
use app\model\system\User;
use app\model\customer\Customer;

class Enterprise extends BaseModel
{

    use SoftDelete;

    //开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    //自动写入时间戳字段
    protected $createTime = 'create_time';

    //定义类型转换
    protected $type = [
        'create_time'  =>  'timestamp:Y年m月d'
    ];

    //软删除字段
    protected $deleteTime = 'delete_time';

    //软删除默认值
    protected $defaultSoftDelete = 0;

    // ===================== 搜索器 =================
    //关键词（搜索企业名称或联系人）
    public function searchKeyAttr($query, $value)
    {
        $query->whereLike('name|contact_person|legal_person', $value);
    }

    //关联客户
    public function searchCustomerIdAttr($query, $value)
    {
        $query->where('customer_id', $value);
    }

    //纳税性质
    public function searchTaxNatureAttr($query, $value)
    {
        $query->where('tax_nature', $value);
    }

    //负责人
    public function searchOwnerUserIdAttr($query, $value)
    {
        $query->where('owner_user_id', $value);
    }

    //添加时间
    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereTime('create_time', 'between', between_time($value));
    }

    // ===================== 获取器 =================
    //纳税性质文本
    public function getTaxNatureTextAttr($value, $data)
    {
        $dict = [0 => '小规模纳税人', 1 => '一般纳税人'];
        return $dict[$data['tax_nature']] ?? '-';
    }

    // ===================== 关联 =================
    //定义用户相对关联
    public function user()
    {
        return $this->belongsTo(User::class, 'owner_user_id', 'id')->bind(['realname']);
    }

    //定义客户关联
    public function customer()
    {
        return $this->belongsTo(Customer::class)->bind(['customer_name' => 'name']);
    }
}
