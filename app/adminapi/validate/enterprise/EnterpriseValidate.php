<?php

namespace app\adminapi\validate\enterprise;

use core\base\BaseValidate;

class EnterpriseValidate extends BaseValidate
{

    protected $rule = [
        'name' => 'require|max:255',
        'customer_id' => 'require',
        'legal_person' => 'max:50',
        'contact_person' => 'max:50',
        'contact_phone' => 'max:20',
        'contact_email' => 'email|max:40',
        'address' => 'max:255',
        'business_scope' => 'max:500',
        'registered_capital' => 'max:50',
        'note' => 'max:255',
    ];

    public $field = [
        'name' => '企业名称',
        'customer_id' => '关联客户',
        'contact_phone' => '联系电话',
        'contact_email' => '联系邮箱',
    ];

}
