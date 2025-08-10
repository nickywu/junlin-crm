<?php

namespace app\adminapi\controller\business;

use core\base\BaseController;
use app\service\business\BusinessService;
use app\adminapi\validate\business\BusinessValidate;

class Business extends BaseController
{

    private $service;

    function __construct(BusinessService $service)
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
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(BusinessValidate $validate)
    {
        $data = $validate->validated();
        $result = $this->service->save($data);
        $result ? $this->success('新增成功') : $this->error('新增失败');
    }

    /**
     * 获取商机组列表
     *
     * @return \think\Response
     */
    public function getGroupList()
    {
        $data = $this->service->getGroupList();
        $this->success($data);
    }



    /**
     * 获取商机阶段
     *
     * @return \think\Response
     */
    public function getBusinessStage()
    {
        $id = $this->request->param('id');
        $data = $this->service->getBusinessStage($id);
        $this->success($data);
    }


    /**
     * 结束商机阶段
     *
     * @return \think\Response
     */
    public function endStage()
    {
        $id = $this->request->param('id');
        $is_end = $this->request->param('is_end');
        $result = $this->service->endStage($id, $is_end);
        $result ? $this->success('推进成功') : $this->error('推进失败');
    }

    
    /**
     * 推进商机阶段
     *
     * @return \think\Response
     */
    public function changeBusinessStage()
    {
        $id = $this->request->param('id');
        $stage_id = $this->request->param('stage_id');
        $result = $this->service->changeBusinessStage($id, $stage_id);
        $result ? $this->success('推进成功') : $this->error('推进失败');
    }


    /**
     * 获取商机产品
     *
     * @return \think\Response
     */
    public function getProduct()
    {
        $id = $this->request->param('id');
        $data = $this->service->getProduct($id);
        $this->success($data);
    }


    /**
     * 更换负责人
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function changeOwnerUser()
    {
        $ids = $this->request->param('ids/a');
        $owner_user_id = $this->request->param('owner_user_id');
        if (!$ids)  $this->error('商机数据不能为空');
        if (!$owner_user_id)  $this->error('负责人不能为空');
        $result = $this->service->changeOwnerUser($ids, $owner_user_id);
        $result ? $this->success('更换成功') : $this->error($this->service->getError());
    }

    /**
     * 更新
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function update($id, BusinessValidate $validate)
    {
        $data = $validate->validated();
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }



    /**
     * 删除
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete()
    {
        $id = $this->request->param('id/a');
        $result = $this->service->delete($id);
        $result ? $this->success('删除成功') : $this->error($this->service->getError());
    }
}
