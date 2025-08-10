<?php

namespace app\model\business;

use core\base\BaseModel;
use think\model\concern\SoftDelete;
use app\model\system\User;
use app\model\contacts\Contacts;
use app\model\customer\Customer;

class Business extends BaseModel
{
    use SoftDelete;

    //开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    //自动写入时间戳字段
    protected $createTime = 'create_time';
    //定义类型转换
    protected $type = [
        'create_time'  =>  'timestamp:Y年m月d',
        'deal_time' => 'timestamp:Y-m-d'
    ];

    const END_STATUS = [0 => '结束', 1 => '赢单', 2 => '输单', 3 => '无效'];

    //软删除字段
    protected $deleteTime = 'delete_time';

    //软删除默认值
    protected $defaultSoftDelete = 0;

    //关键词
    public function searchNameAttr($query, $value)
    {
        $query->whereLike('name', $value);
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

    //成交时间
    public function searchDealTimeAttr($query, $value)
    {
        $query->whereTime('deal_time', 'between', between_time($value));
    }

    //商机阶段
    public function searchStageIdAttr($query, $value)
    {
        $group_id = $value[0];
        $stage_id = $value[1];
        if (in_array($stage_id, array_keys(self::END_STATUS))) {
            $query->where('is_end', $stage_id);
        } else {
            $query->where('group_id', $group_id)->where('stage_id',  $stage_id)->where('is_end', 0);
        }
    }

    //负责人
    public function searchOwnerUserIdAttr($query, $value)
    {
        $query->where('owner_user_id', $value);
    }


    //最后跟进时间
    public function getLastTimeTextAttr($value, $data)
    {
        return time_format($data['last_time']) ?? '-';
    }

    //赢单输单
    public function getEndTextAttr($value, $data)
    {
        return self::END_STATUS[$data['is_end']] ?? '-';
    }


    public function setDealTimeAttr($value)
    {
        return strtotime($value);
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


    //定义产品关联
    public function product()
    {
        return $this->hasMany(BusinessProduct::class, 'business_id', 'id');
    }

    //定义商机组关联
    public function businessGroup()
    {
        return $this->belongsTo(BusinessGroup::class, 'group_id')->bind(['group_name' => 'name']);
    }


    //定义商机阶段关联
    public function businessStage()
    {
        return $this->belongsTo(BusinessStage::class, 'stage_id')->bind(['stage_name' => 'name']);
    }
}
