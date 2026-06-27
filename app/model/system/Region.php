<?php

namespace app\model\system;

use core\base\BaseModel;

/**
 * 省市区地区模型
 * 用于存储中国省市区三级地区数据，支持级联查询
 */
class Region extends BaseModel
{
    /** @var string 数据表名 */
    protected $table = 'speed_region';

    /** @var bool 关闭自动写入时间戳（该表不需要时间戳） */
    protected $autoWriteTimestamp = false;

    /** @var string 主键字段 */
    protected $pk = 'id';

    /**
     * 获取所有省份列表（level=1）
     *
     * @return array
     */
    public function getProvinceList()
    {
        return $this->where('level', 0)->field(['id', 'name'])->select();
    }

    /**
     * 根据父级ID获取子地区列表
     *
     * @param  int  $parentId  父级地区ID
     * @return array
     */
    public function getListByParentId($parentId)
    {
        return $this->where('parent_id', $parentId)->field(['id', 'name'])->select();
    }
}
