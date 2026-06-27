<?php

namespace app\adminapi\validate\collect;

use core\base\BaseValidate;

class LongCollectValidate extends BaseValidate
{
    protected $rule = [
        'contract_product_id' => 'require',
        'contract_id' => 'require',
        'customer_id' => 'require',
        'account' => 'require',
        'collect_time' => 'require',
        'amount' => 'require|validNumber',
        'monthly_fee' => 'require|validNumber',
        'collect_months' => 'require|number',
        'pay_type' => 'require',
    ];

    public $field = [
        'contract_product_id' => '合同关联产品',
        'contract_id' => '关联合同',
        'customer_id' => '关联客户',
        'account' => '收款账号',
        'collect_time' => '收款时间',
        'amount' => '收款金额',
        'monthly_fee' => '月服务费',
        'collect_months' => '收款月数',
        'pay_type' => '付款类型',
    ];

    protected $message = [
        'amount.validNumber' => '收款金额请输入有效的数字',
        'monthly_fee.validNumber' => '月服务费请输入有效的数字',
        'collect_months.number' => '收款月数必须是数字',
    ];
}
