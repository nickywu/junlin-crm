<?php

namespace app\service\action_log;

use core\base\BaseService;
use app\model\action_log\ActionLog;
use app\model\record\Record;
class ActionLogService extends BaseService
{


    public function __construct(ActionLog $model)
    {
        $this->model = $model;
    }



    /**
     * 获取列表
     * @return array
     */
    public function getList($type,$data_id)
    {
        $map['type'] = Record::getTypeValue($type);
        $map['data_id'] = $data_id;
        $data = $this->model->where($map)->order('id','desc')->with(['user'])->paginate();
        return $data;
    }


}
