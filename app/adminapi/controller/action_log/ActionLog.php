<?php

namespace app\adminapi\controller\action_log;

use core\base\BaseController;
use app\service\action_log\ActionLogService;

class ActionLog extends BaseController
{

    private $service;

    function __construct(ActionLogService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $type = $this->request->param('type');
        $data_id = $this->request->param('data_id');
        $data = $this->service->getList($type,$data_id);
        $this->success($data);
    }


}