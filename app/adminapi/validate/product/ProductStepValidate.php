<?php

namespace app\adminapi\validate\product;

use core\base\BaseValidate;

class ProductStepValidate extends BaseValidate
{
    protected $rule = [
        'product_id' => 'require|integer',
        'name'       => 'require|max:255',
        'attention'  => 'max:500',
        'next_step_name' => 'max:255',
        'sort'       => 'integer',
    ];

    public $field = [
        'product_id' => '关联产品',
        'name'       => '步骤名称',
        'attention'  => '注意事项',
        'next_step_name' => '下一步骤名称',
        'sort'       => '序号',
    ];
}
