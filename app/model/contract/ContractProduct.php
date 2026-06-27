<?php

namespace app\model\contract;

use core\base\BaseModel;
use  app\model\product\Product;
use app\model\system\User;

class ContractProduct extends BaseModel
{
    public const FINISH_STATUS_TEXT = [
        0 => '未完结',
        1 => '已完结',
        2 => '异常终止',
    ];

    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_time';

    //定义产品相对关联
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')->bind(['name']);
    }

    /**
     * 定义负责人关联
     *
     * @return \think\model\relation\BelongsTo
     */
    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owner_user_id', 'id')->bind(['owner_user_name' => 'realname']);
    }

    /**
     * 定义工单主管关联
     *
     * @return \think\model\relation\BelongsTo
     */
    public function workManager()
    {
        return $this->belongsTo(User::class, 'work_manager_id', 'id')->bind(['work_manager_name' => 'realname']);
    }

    /**
     * 获取完结状态文本
     *
     * @param mixed $value 原始值
     * @param array $data 当前数据
     * @return string
     */
    public function getFinishStatusTextAttr($value, $data)
    {
        return self::FINISH_STATUS_TEXT[$data['finish_status']] ?? '-';
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
}
