<?php

namespace app\adminapi\controller\record;

use core\base\BaseController;
use app\service\record\RecordService;
use app\adminapi\validate\record\RecordValidate;

class Record extends BaseController
{

    private $service;

    function __construct(RecordService $service)
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
        $record_type = $this->request->param('record_type');
        $data_id  = $this->request->param('data_id');
        $data = $this->service->getList($record_type, $data_id);
        $this->success($data);
    }


    /**
     * 新增
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(RecordValidate $validate)
    {
        $data = $validate->validated();
        $result = $this->service->save($data);
        $result ? $this->success('新增成功') : $this->error('新增失败');
    }


    /**
     * 删除
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $result = $this->service->delete($id);
        $result ? $this->success('删除成功') : $this->error('删除失败');
    }
}
