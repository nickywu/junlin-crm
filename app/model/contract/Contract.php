<?php

namespace app\model\contract;

use core\base\BaseModel;
use think\model\concern\SoftDelete;
use app\model\system\User;
use app\model\customer\Customer;
use app\model\business\Business;
use app\model\contacts\Contacts;

class Contract extends BaseModel
{

    use SoftDelete;

    //开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    //自动写入时间戳字段
    protected $createTime = 'create_time';

    //定义类型转换
    protected $type = [
        'create_time'  =>  'timestamp:Y年m月d',
        'start_time' => 'timestamp:Y-m-d',
        'end_time' => 'timestamp:Y-m-d',
        'signing_time' => 'timestamp:Y-m-d'
    ];

    //软删除字段
    protected $deleteTime = 'delete_time';

    //软删除默认值
    protected $defaultSoftDelete = 0;

    //关键词
    public function searchKeyAttr($query, $value)
    {
        $query->whereLike('name|last_record|order_no', $value);
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

    //商机
    public function searchBusinessIdAttr($query, $value)
    {
        $query->where('business_id', $value);
    }

    //联系人
    public function searchContactsIdAttr($query, $value)
    {
        $query->where('contacts_id', $value);
    }

    //签约时间
    public function searchSigningTimeAttr($query, $value)
    {
        $query->whereTime('signing_time', 'between', between_time($value));
    }

    //状态
    public function searchStatusAttr($query, $value)
    {
        $query->where('status', $value);
    }

    //添加时间
    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereTime('create_time', 'between', between_time($value));
    }

    //开始时间
    public function searchStartTimeAttr($query, $value)
    {
        $query->whereTime('start_time', 'between', between_time($value));
    }


    //结束时间
    public function searchEndTimeAttr($query, $value)
    {
        $query->whereTime('end_time', 'between', between_time($value));
    }


    //负责人
    public function searchOwnerUserIdAttr($query, $value)
    {
        $query->where('owner_user_id', $value);
    }

    //公司签约人
    public function searchOrderUserIdAttr($query, $value)
    {
        $query->where('order_user_id', $value);
    }

    //定义客公司签约人相对关联
    public function orderUser()
    {
        return $this->belongsTo(User::class, 'order_user_id', 'id')->bind(['realname']);
    }

    //定义负责人相对关联
    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owner_user_id', 'id')->field(['id,realname']);
    }



    //定义客户关联
    public function customer()
    {
        return $this->belongsTo(Customer::class)->bind(['customer_name' => 'name']);
    }

    //定义商机关联
    public function business()
    {
        return $this->belongsTo(Business::class)->bind(['business_name' => 'name']);
    }

    //定义联系人关联
    public function contacts()
    {
        return $this->belongsTo(Contacts::class)->bind(['contacts_name' => 'name']);
    }

    //定义合同产品关联
    public function contractProduct()
    {
        return $this->hasMany(ContractProduct::class, 'contract_id', 'id');
    }

    //状态
    public function getStatusTextAttr($value, $data)
    {
        return get_dict_map('contract_status', $data['status']);
    }


    //最后跟进时间
    public function getLastTimeTextAttr($value, $data)
    {
        return time_format($data['last_time']) ?? '-';
    }

    //产品总金额
    public function setTotalPriceAttr($value, $data)
    {
        if (empty($data['product'])) return $value;
        return array_sum(array_column($data['product'], 'total_price'));
    }

    //模型事件写入前
    public static function onBeforeWrite($model)
    {
        //开始日期
        $model->set('start_time', strtotime($model->start_time));
        //结束日期
        $model->set('end_time', strtotime($model->end_time));
        //签约日期
        $model->set('signing_time', strtotime($model->signing_time));
    }
}
