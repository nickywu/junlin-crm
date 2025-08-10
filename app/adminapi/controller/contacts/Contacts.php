<?php

namespace app\adminapi\controller\contacts;

use core\base\BaseController;
use app\service\contacts\ContactsService;
use app\adminapi\validate\contacts\ContactsValidate;

class Contacts extends BaseController
{

    private $service;

    function __construct(ContactsService $service)
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
    public function save(ContactsValidate $validate)
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
    public function update($id, ContactsValidate $validate)
    {
        $data = $validate->validated();
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
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
        if (!$ids) $this->error('联系人数据不能为空');
        if (!$owner_user_id) $this->error('负责人不能为空');
        $result = $this->service->changeOwnerUser($ids, $owner_user_id);
        $result ? $this->success('更换负责人成功') : $this->error($this->service->getError());
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
