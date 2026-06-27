<?php

namespace app\adminapi\controller\system;

use core\base\BaseController;
use app\model\system\Region as RegionModel;

/**
 * 省市区地区控制器
 * 提供省市区三级地区数据的查询接口，用于前端表单的级联选择
 */
class Region extends BaseController
{

    /** @var RegionModel 地区模型实例 */
    private $model;

    /**
     * 构造方法，注入地区模型
     *
     * @param RegionModel $model
     */
    public function __construct(RegionModel $model)
    {
        parent::__construct();
        $this->model = $model;
    }

    /**
     * 获取省份列表（一级地区）
     *
     * @return \think\Response
     */
    public function getProvince()
    {
        $data = $this->model->getProvinceList();
        $this->success($data);
    }

    /**
     * 根据父级ID获取子地区列表
     * 用于城市（二级）和区县（三级）的动态加载
     *
     * @return \think\Response
     */
    public function getChildren()
    {
        $parentId = $this->request->param('parent_id/d', 0);
        if (!$parentId) {
            $this->error('父级地区ID不能为空');
        }
        $data = $this->model->getListByParentId($parentId);
        $this->success($data);
    }
}
