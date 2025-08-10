<?php
namespace app\model\business;
use core\base\BaseModel;

class BusinessStage extends BaseModel {

    /**
     * 查询列表
     * @return array
     */
    public function getList(){
        $data = $this->order('id','asc')->select();
        return $data;
    }

}	