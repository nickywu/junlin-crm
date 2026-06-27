<?php

namespace app\model\contract;

use core\base\BaseModel;
use think\model\concern\SoftDelete;
use app\model\system\User;

class ServicePeriod extends BaseModel
{
    use SoftDelete;

    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_time';

    protected $deleteTime = 'delete_time';

    protected $defaultSoftDelete = 0;

    public const STATUS_TEXT = [
        0 => '未开始',
        1 => '办理中',
        2 => '已完结',
        3 => '异常',
    ];

    /**
     * 关联合同子业务
     *
     * @return \think\model\relation\BelongsTo
     */
    public function contractProduct()
    {
        return $this->belongsTo(ContractProduct::class, 'contract_product_id', 'id')->bind(['product_name']);
    }

    /**
     * 关联负责人
     *
     * @return \think\model\relation\BelongsTo
     */
    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owner_user_id', 'id')->bind(['owner_user_name' => 'realname']);
    }

    /**
     * 获取周期状态文本
     *
     * @param mixed $value 原始值
     * @param array $data 当前数据
     * @return string
     */
    public function getStatusTextAttr($value, $data)
    {
        return self::STATUS_TEXT[$data['status']] ?? '-';
    }

    /**
     * 搜索合同
     *
     * @param mixed $query 查询对象
     * @param mixed $value 合同ID
     * @return void
     */
    public function searchContractIdAttr($query, $value)
    {
        $query->where('contract_id', $value);
    }

    /**
     * 搜索合同子业务
     *
     * @param mixed $query 查询对象
     * @param mixed $value 合同子业务ID
     * @return void
     */
    public function searchContractProductIdAttr($query, $value)
    {
        $query->where('contract_product_id', $value);
    }

    /**
     * 搜索周期状态
     *
     * @param mixed $query 查询对象
     * @param mixed $value 状态
     * @return void
     */
    public function searchStatusAttr($query, $value)
    {
        $query->where('status', $value);
    }
}
