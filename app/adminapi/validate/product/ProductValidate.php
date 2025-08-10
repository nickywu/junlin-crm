<?php

namespace app\adminapi\validate\product;

use core\base\BaseValidate;

class ProductValidate extends BaseValidate
{


    protected $rule = [
        'name' =>  'require|unique:product,delete_time=0|max:255',
        'category'=>'require',
        'price'=>'require|validNumber',
        'remark'=>'max:255'
    ];


    public $field = [
        'name'=>'产品名称',
        'category'=>'产品类别',
        'price'=>'产品价格'
    ];  

    protected $message = [
      'name.unique' => '产品名称已存在,请修改后重新提交',
      'price.validNumber'=>'产品价格请输入有效的数字'
    ];

    
}