<?php

namespace app\adminapi\controller\clues;

use core\base\BaseController;
use app\service\clues\CluesService;
use app\adminapi\validate\clues\CluesValidate;

class Clues extends BaseController
{

    private $service;

    function __construct(CluesService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $param = request()->param();
        $param['is_transform'] = $param['is_transform'] ?? 0;
        $data = $this->service->getList($param);
        return $this->success($data);
    }


    /**
     * 显示详情资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function read($id)
    {
        $data = $this->service->read($id);
        $data ? $this->success($data) : $this->error('数据不存在');
    }


    /**
     * 显示编辑资源表单页
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $data = $this->service->edit($id);
        return $this->success($data);
    }



    /**
     * 新增
     *
     * @return \think\Response
     */
    public function save(CluesValidate $validate)
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
    public function update($id, CluesValidate $validate)
    {
        $data = $validate->validated();
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }

    /**
     * 分配线索
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function allocation()
    {
        $ids = $this->request->param('ids/a');
        $owner_user_id = $this->request->param('owner_user_id');
        if (!$ids)  $this->error('线索数据不能为空');
        if (!$owner_user_id)  $this->error('负责人不能为空');
        $result = $this->service->allocation($ids, $owner_user_id);
        $result ? $this->success('分配成功') : $this->error($this->service->getError());
    }



    /**
     * 转换成客户
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function transform()
    {
        $id = $this->request->param('id');
        if (!$id)  $this->error('线索数据不能为空');
        $result = $this->service->transform($id);
        $result ? $this->success('转换成功') : $this->error('转换失败');
    }


    /**
     * 删除
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete()
    {
        $id = $this->request->param('id/a');;
        $result = $this->service->delete($id);
        $result ? $this->success('删除成功') : $this->error($this->service->getError());
    }
}
