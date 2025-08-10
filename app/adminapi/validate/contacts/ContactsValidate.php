<?php

namespace app\adminapi\validate\contacts;

use core\base\BaseValidate;

class ContactsValidate extends BaseValidate
{


    protected $rule = [
        'name' =>  'require|max:30',
        'customer_id' => 'require',
        'mobile'  =>  'mobile',
        'email' => 'email|max:40',
        'telephone' => 'max:50',
        'note' => 'max:255',
        'addr' => 'max:255',
    ];


    public $field = [
        'name' => '联系人名称',
        'mobile' => '手机号码',
        'email' => '邮箱',
        'customer_id' => '客户'
    ];


}
