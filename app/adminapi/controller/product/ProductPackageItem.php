<?php

namespace app\adminapi\controller\product;

use core\base\BaseController;
use app\service\product\ProductPackageItemService;

class ProductPackageItem extends BaseController
{
    private $service;

    /**
     * 初始化产品套餐子业务模板控制器
     *
     * @param ProductPackageItemService $service 套餐子业务模板服务
     * @return void
     */
    public function __construct(ProductPackageItemService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 获取套餐子业务模板列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data = $this->service->getList();
        $this->success($data);
    }

    /**
     * 获取套餐子业务模板编辑数据
     *
     * @param int $id 模板ID
     * @return \think\Response
     */
    public function edit($id)
    {
        $data = $this->service->edit($id);
        $this->success($data);
    }

    /**
     * 保存套餐子业务模板
     *
     * @return \think\Response
     */
    public function save()
    {
        $data = $this->request->param();
        $result = $this->service->save($data);
        $result ? $this->success('新增成功') : $this->error('新增失败');
    }

    /**
     * 更新套餐子业务模板
     *
     * @param int $id 模板ID
     * @return \think\Response
     */
    public function update($id)
    {
        $data = $this->request->param();
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }

    /**
     * 删除套餐子业务模板
     *
     * @return \think\Response
     */
    public function delete()
    {
        $ids = $this->request->param('id/a');
        $result = $this->service->delete($ids);
        $result ? $this->success('删除成功') : $this->error('删除失败');
    }
}
