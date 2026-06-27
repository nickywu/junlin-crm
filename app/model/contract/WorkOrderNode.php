<?php

namespace app\model\contract;

use core\base\BaseModel;
use app\model\system\User;

class WorkOrderNode extends BaseModel
{
    public const STATUS_TEXT = [
        0 => '未开始',
        1 => '办理中',
        2 => '已完成',
        3 => '异常',
        4 => '跳过',
    ];

    /**
     * 关联工单
     *
     * @return \think\model\relation\BelongsTo
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id', 'id')->bind(['work_order_no', 'work_order_title' => 'title']);
    }

    /**
     * 关联节点负责人
     *
     * @return \think\model\relation\BelongsTo
     */
    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owner_user_id', 'id')->bind(['owner_user_name' => 'realname']);
    }

    /**
     * 获取节点状态文本
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
     * 按工单搜索节点
     *
     * @param mixed $query 查询对象
     * @param mixed $value 工单ID
     * @return void
     */
    public function searchWorkOrderIdAttr($query, $value)
    {
        $query->where('work_order_id', $value);
    }

    /**
     * 按合同搜索节点
     *
     * @param mixed $query 查询对象
     * @param mixed $value 合同ID
     * @return void
     */
    public function searchContractIdAttr($query, $value)
    {
        $workOrderIds = WorkOrder::where('contract_id', $value)->column('id');
        $query->whereIn('work_order_id', $workOrderIds ?: [0]);
    }
}
