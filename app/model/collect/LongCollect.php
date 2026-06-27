<?php

namespace app\model\collect;

use core\base\BaseModel;
use think\model\concern\SoftDelete;
use app\model\contract\ContractProduct;
use app\model\contract\Contract;
use app\model\customer\Customer;
use app\model\system\User;

class LongCollect extends BaseModel
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

    // 定义合同关联产品关联
    public function contractProduct()
    {
        return $this->belongsTo(ContractProduct::class, 'contract_product_id', 'id');
    }

    // 定义合同关联
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id')->bind(['contract_name' => 'name']);
    }

    // 定义客户关联
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id')->bind(['customer_name' => 'name']);
    }

    // 定义创建人关联
    public function createUser()
    {
        return $this->belongsTo(User::class, 'create_user_id', 'id')->bind(['create_realname' => 'realname']);
    }

    // 付款类型获取器
    public function getPayTypeTextAttr($value, $data)
    {
        return get_dict_map('pay_type', $data['pay_type']);
    }

    // 关键词搜索
    public function searchKeyAttr($query, $value)
    {
        $query->whereLike('account|remark', $value);
    }

    // 关联合同搜索
    public function searchContractIdAttr($query, $value)
    {
        $query->where('contract_id', $value);
    }

    // 关联客户搜索
    public function searchCustomerIdAttr($query, $value)
    {
        $query->where('customer_id', $value);
    }

    // 收款时间搜索
    public function searchCollectTimeAttr($query, $value)
    {
        $query->whereTime('collect_time', 'between', between_time($value));
    }

    // 创建时间搜索
    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereTime('create_time', 'between', between_time($value));
    }

    // 付款类型搜索
    public function searchPayTypeAttr($query, $value)
    {
        $query->where('pay_type', $value);
    }
}
