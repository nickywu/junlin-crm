<?php

namespace app\adminapi\controller\contract;

use core\base\BaseController;
use app\service\contract\WorkOrderNodeService;

class WorkOrderNode extends BaseController
{
    private $service;

    /**
     * 初始化工单节点控制器
     *
     * @param WorkOrderNodeService $service 工单节点服务
     * @return void
     */
    public function __construct(WorkOrderNodeService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 获取工单节点列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data = $this->service->getList();
        $this->success($data);
    }

    /**
     * 更新工单节点
     *
     * @param int $id 节点ID
     * @return \think\Response
     */
    public function update($id)
    {
        $data = $this->request->param();
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }
}
