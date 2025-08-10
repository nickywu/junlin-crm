<?php

namespace app\adminapi\validate\clues;

use core\base\BaseValidate;

class CluesValidate extends BaseValidate
{

    protected $rule = [
        'name' =>  'require|max:30',
        'source' =>  'require',
        'mobile'  =>  'mobile',
        'email' => 'email|max:40',
        'telephone' => 'max:50',
        'remark' => 'max:255',
        'addr' => 'max:255',
    ];



    public $field = [
        'name' => '线索名称',
        'source' => '线索来源',
        'mobile' => '手机号码',
        'email' => '邮箱'
    ];
}
