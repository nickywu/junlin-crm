<?php

namespace app\adminapi\controller\contract;

use core\base\BaseController;
use app\service\contract\ServicePeriodService;

class ServicePeriod extends BaseController
{
    private $service;

    /**
     * 初始化服务周期控制器
     *
     * @param ServicePeriodService $service 服务周期服务
     * @return void
     */
    public function __construct(ServicePeriodService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 获取服务周期列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data = $this->service->getList();
        $this->success($data);
    }

    /**
     * 更新服务周期
     *
     * @param int $id 周期ID
     * @return \think\Response
     */
    public function update($id)
    {
        $data = $this->request->param();
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }
}
