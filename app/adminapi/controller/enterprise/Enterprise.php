<?php

namespace app\adminapi\controller\enterprise;

use core\base\BaseController;
use app\service\enterprise\EnterpriseService;
use app\adminapi\validate\enterprise\EnterpriseValidate;

class Enterprise extends BaseController
{

    private $service;

    function __construct(EnterpriseService $service)
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
        $data = $this->service->getList();
        $this->success($data);
    }

    /**
     * 获取编辑的数据
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $data = $this->service->edit($id);
        $this->success($data);
    }

    /**
     * 获取详情的数据
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $data = $this->service->read($id);
        $this->success($data);
    }

    /**
     * 新增
     *
     * @return \think\Response
     */
    public function save(EnterpriseValidate $validate)
    {
        $data = $validate->validated();
        $result = $this->service->save($data);
        $result ? $this->success('新增成功') : $this->error('新增失败');
    }

    /**
     * 更新
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function update($id, EnterpriseValidate $validate)
    {
        $data = $validate->validated();
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }

    /**
     * 删除
     *
     * @return \think\Response
     */
    public function delete()
    {
        $id = $this->request->param('id/a');
        $result = $this->service->delete($id);
        $result ? $this->success('删除成功') : $this->error($this->service->getError());
    }
}
