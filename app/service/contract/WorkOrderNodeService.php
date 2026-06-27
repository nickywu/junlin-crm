<?php

namespace app\service\contract;

use core\base\BaseService;
use app\model\contract\WorkOrderNode;

class WorkOrderNodeService extends BaseService
{
    /**
     * 初始化工单节点服务
     *
     * @param WorkOrderNode $model 工单节点模型
     * @return void
     */
    public function __construct(WorkOrderNode $model)
    {
        $this->model = $model;
    }

    /**
     * 获取工单节点列表
     *
     * @return mixed
     */
    public function getList()
    {
        return $this->model
            ->search()
            ->with(['workOrder', 'ownerUser'])
            ->append(['status_text'])
            ->order('work_order_id', 'asc')
            ->order('sort', 'asc')
            ->paginate();
    }

    /**
     * 更新工单节点
     *
     * @param int $id 节点ID
     * @param array $data 节点数据
     * @return bool
     */
    public function update($id, array $data)
    {
        if (($data['status'] ?? null) == 2 && empty($data['finish_time'])) {
            // 节点完成时自动写入完成时间，保持流程节点可追踪。
            $data['finish_time'] = time();
        }
        return $this->model->updateBy($id, $data);
    }
}
