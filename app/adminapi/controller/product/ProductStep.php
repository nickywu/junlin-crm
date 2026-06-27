<?php

namespace app\adminapi\controller\product;

use core\base\BaseController;
use app\service\product\ProductStepService;
use app\adminapi\validate\product\ProductStepValidate;

class ProductStep extends BaseController
{
    private $service;

    function __construct(ProductStepService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 列表
     * @return \think\Response
     */
    public function index()
    {
        $data = $this->service->getList();
        $this->success($data);
    }

    /**
     * 获取编辑数据
     * @param int $id 步骤ID
     * @return \think\Response
     */
    public function edit($id)
    {
        $data = $this->service->edit($id);
        $this->success($data);
    }

    /**
     * 新增
     * @param ProductStepValidate $validate
     * @return \think\Response
     */
    public function save(ProductStepValidate $validate)
    {
        $data = $validate->validated();
        $result = $this->service->save($data);
        $result ? $this->success('新增成功') : $this->error('新增失败');
    }

    /**
     * 更新
     * @param int $id 步骤ID
     * @param ProductStepValidate $validate
     * @return \think\Response
     */
    public function update($id, ProductStepValidate $validate)
    {
        $data = $validate->validated();
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }

    /**
     * 删除
     * @return \think\Response
     */
    public function delete()
    {
        $ids = $this->request->param('id/a');
        $result = $this->service->delete($ids);
        $result ? $this->success('删除成功') : $this->error($this->service->getError());
    }
}
