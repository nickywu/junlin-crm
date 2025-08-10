<?php

namespace app\model\action_log;

use core\base\BaseModel;
use app\model\system\User;

class ActionLog extends BaseModel
{

    //定义用户相对关联
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->bind(['realname']);
    }
}
