<?php

namespace app\adminapi\validate\business;

use core\base\BaseValidate;

class BusinessValidate extends BaseValidate
{


    protected $rule = [
        'name' => 'require|max:150',
        'customer_id' => 'require',
        'group_id' => 'require',
        'stage_id' => 'require',
        'money' => 'require|validNumber|between:1,99999999.99',
        'deal_time' => 'require',
        'product' => 'array',
        'product.*.price' => 'requireWith:product|validNumber',
        'product.*.sale_price' => 'requireWith:product|validNumber',
        'product.*.number' => 'requireWith:product|validNumber',
        'product.*.unit' => 'requireWith:product',
        'product.*.category' => 'requireWith:product',
        'product.*.product_id' => 'requireWith:product',
    ];


    public $field = [
        'name' => '商机名称',
        'customer_id' => '关联客户',
        'stage_id' => '商机阶段',
        'money' => '商机金额',
        'deal_time' => '预计成交时间',
        'product' => '商机产品',
        'group_id' => '商机组'
    ];


    protected $message = [
        'product.array' => '产品信息必须是数组格式',
        'product.*.price.requireWith' => '请输入产品价格',
        'product.*.price.validNumber' => '产品价格必须是有效数字',
        'product.*.sale_price.requireWith' => '请输入销售价格',
        'product.*.sale_price.validNumber' => '销售价格必须是有效数字',
        'product.*.number.requireWith' => '请输入产品数量',
        'product.*.number.validNumber' => '产品数量必须是有效数字',
        'product.*.unit.requireWith' => '请选择产品单位',
        'product.*.category.requireWith' => '请选择产品类别',
        'product.*.product_id.requireWith' => '请选择产品',
    ];
}
