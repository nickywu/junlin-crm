<?php

namespace app\model\business;

use core\base\BaseModel;

class BusinessGroup extends BaseModel
{

    //定义商机阶段相对关联
    public function stage()
    {
        return $this->hasMany(BusinessStage::class, 'group_id');
    }


}
