<?php

namespace app\adminapi\validate\customer;

use core\base\BaseValidate;

class CustomerValidate extends BaseValidate
{

    protected $rule = [
        'name' =>  'require|unique:customer,delete_time=0|max:255',
        'mobile'  =>  'mobile',
        'email' => 'email|max:40',
        'telephone' => 'max:50',
        'note' => 'max:255',
        'addr' => 'max:255',
    ];


    public $field = [
        'name'=>'客户名称',
        'mobile'=>'手机号码',
        'email'=>'邮箱'
    ];  

    protected $message = [
      'name.unique' => '客户名称已存在,请修改后重新提交',
    ];
    
}