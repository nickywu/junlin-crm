<?php

namespace app\adminapi\controller\contract;

use core\base\BaseController;
use app\service\contract\WorkOrderService;

class WorkOrder extends BaseController
{
    private $service;

    /**
     * 初始化工单控制器
     *
     * @param WorkOrderService $service 工单服务
     * @return void
     */
    public function __construct(WorkOrderService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 获取工单列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data = $this->service->getList();
        $this->success($data);
    }

    /**
     * 更新工单
     *
     * @param int $id 工单ID
     * @return \think\Response
     */
    public function update($id)
    {
        $data = $this->request->param();
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }
}
