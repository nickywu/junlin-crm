<?php

namespace app\model\contacts;

use core\base\BaseModel;
use think\model\concern\SoftDelete;
use app\model\system\User;
use app\model\customer\Customer;
use core\facade\Util;

class Contacts extends BaseModel
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


    //关键词
    public function searchKeyAttr($query, $value)
    {
        $query->whereLike('name|mobile|job', $value);
    }

    //地址
    public function searchAddrAttr($query, $value)
    {
        $query->whereLike('addr', $value);
    }

    //最后跟进
    public function searchLastRecordAttr($query, $value)
    {
        $query->whereLike('last_record', $value);
    }

    //客户
    public function searchCustomerIdAttr($query, $value)
    {
        $query->where('customer_id', $value);
    }

    //添加时间
    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereTime('create_time', 'between', between_time($value));
    }

    //关键决策人
    public function searchIsDecisionAttr($query, $value)
    {
        $query->where('is_decision', $value);
    }

    //负责人
    public function searchOwnerUserIdAttr($query, $value)
    {
        $query->where('owner_user_id', $value);
    }

    //性别
    public function searchGenderAttr($query, $value)
    {
        $query->where('gender', $value);
    }

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

    //关键决策人
    public function getDecisionTextAttr($value, $data)
    {
        $dict_cache = [0 => '否', 1 => '是'];
        return $dict_cache[$data['is_decision']] ?? '-';
    }

    //性别
    public function getGenderTextAttr($value, $data)
    {
        return get_dict_map('gender', $data['gender']);
    }

    //职务
    public function getJobTextAttr($value, $data)
    {
        return get_dict_map('job', $data['job']);
    }

    //最后跟进时间
    public function getLastTimeTextAttr($value, $data)
    {
        return time_format($data['last_time']) ?? '-';
    }


    //地址修改器
    public function setAddrAttr($value)
    {
        $this->set('gps', Util::addrToGps($value));
        return $value;
    }
}
