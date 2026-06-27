<?php

namespace app\adminapi\controller\collect;

use core\base\BaseController;
use app\service\collect\SingleCollectService;
use app\adminapi\validate\collect\SingleCollectValidate;

class SingleCollect extends BaseController
{
    private $service;

    function __construct(SingleCollectService $service)
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
     * @param int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $data = $this->service->edit($id);
        $this->success($data);
    }

    /**
     * 新增
     *
     * @param \think\Request $request
     * @return \think\Response
     */
    public function save(SingleCollectValidate $validate)
    {
        $data = $validate->validated();
        $result = $this->service->save($data);
        $result ? $this->success('新增成功') : $this->error('新增失败');
    }

    /**
     * 更新
     *
     * @param int $id
     * @return \think\Response
     */
    public function update($id, SingleCollectValidate $validate)
    {
        $data = $validate->validated();
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }

    /**
     * 删除
     *
     * @param int $id
     * @return \think\Response
     */
    public function delete()
    {
        $id = $this->request->param('id/a');
        $result = $this->service->delete($id);
        $result ? $this->success('删除成功') : $this->error('删除失败');
    }

    /**
     * 获取单次服务合同产品列表（供TableSelect下拉用）
     *
     * @return \think\Response
     */
    public function getContractProducts()
    {
        $params = $this->request->param();
        $data = $this->service->getContractProducts($params);
        $this->success($data);
    }
}
