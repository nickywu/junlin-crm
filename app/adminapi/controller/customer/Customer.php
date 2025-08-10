<?php

namespace app\adminapi\controller\customer;

use core\base\BaseController;
use app\service\customer\CustomerService;
use app\adminapi\validate\customer\CustomerValidate;

class Customer extends BaseController
{

    private $service;

    function __construct(CustomerService $service)
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
    public function save(CustomerValidate $validate)
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
    public function update($id,CustomerValidate $validate)
    {
        $data = $validate->validated();
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }


    /**
     * 分配客户
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function allocation()
    {
        $ids = $this->request->param('ids/a');
        $owner_user_id = $this->request->param('owner_user_id');
        if (!$ids)  $this->error('客户数据不能为空');
        if (!$owner_user_id)  $this->error('负责人不能为空');
        $result = $this->service->allocation($ids, $owner_user_id);
        $result ? $this->success('分配成功') : $this->error($this->service->getError()); 
    }


    /**
     * 更改成交状态
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function changeDealStatus(){
        $id = $this->request->param('ids/a');
        $status = $this->request->param('deal_status',0);
        if (!$id)  $this->error('客户数据不能为空');
        $result = $this->service->changeDealStatus($id,$status);
        $result ? $this->success('更改成功') : $this->error($this->service->getError()); 
    }



    /**
     * 删除
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(){
      $ids = $this->request->param('id/a');
      $result = $this->service->delete($ids);
      $result ? $this->success('删除成功') : $this->error($this->service->getError()); 
    }

}