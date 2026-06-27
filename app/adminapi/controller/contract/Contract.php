<?php

namespace app\adminapi\controller\contract;

use core\base\BaseController;
use app\service\contract\ContractService;
use app\adminapi\validate\contract\ContractValidate;


class Contract extends BaseController
{

    private $service;

    function __construct(ContractService $service)
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
     * 产品列表
     *
     * @param int $id
     * @return \think\Response
     */
    public function getProduct()
    {
        $id = $this->request->param('id');
        $data = $this->service->getProduct($id);
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
    public function save(ContractValidate $validate)
    {
        $data = $validate->validated();
        $result = $this->service->save($data);
        $result ? $this->success('新增成功') : $this->error('新增失败');
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
        if (!$ids)  $this->error('合同数据不能为空');
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
    public function update($id, ContractValidate $validate)
    {
        $data = $validate->validated();
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }


    /**
     * 重新生成合同服务周期和工单
     *
     * @return \think\Response
     */
    public function generateWorkflow()
    {
        $contract_id = $this->request->param('contract_id');
        if (!$contract_id) {
            $this->error('合同不能为空');
        }
        $result = $this->service->generateWorkflow($contract_id);
        $result ? $this->success('生成成功') : $this->error('生成失败');
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
        $result ? $this->success('删除成功') : $this->error('删除失败');
    }
}
