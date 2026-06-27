<?php

namespace app\service\contract;

use core\base\BaseService;
use app\model\contract\WorkOrder;

class WorkOrderService extends BaseService
{
    /**
     * 初始化工单服务
     *
     * @param WorkOrder $model 工单模型
     * @return void
     */
    public function __construct(WorkOrder $model)
    {
        $this->model = $model;
    }

    /**
     * 获取工单列表
     *
     * @return mixed
     */
    public function getList()
    {
        return $this->model
            ->search()
            ->with(['contractProduct', 'servicePeriod', 'ownerUser'])
            ->append(['status_text'])
            ->order('id', 'desc')
            ->paginate();
    }

    /**
     * 更新工单
     *
     * @param int $id 工单ID
     * @param array $data 工单数据
     * @return bool
     */
    public function update($id, array $data)
    {
        if (($data['status'] ?? null) == 2 && empty($data['finish_time'])) {
            // 完结工单时写入完结时间，便于合同执行进度统计。
            $data['finish_time'] = time();
            $data['progress'] = 100;
        }
        return $this->model->updateBy($id, $data);
    }
}
