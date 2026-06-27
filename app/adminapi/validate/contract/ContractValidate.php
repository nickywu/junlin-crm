<?php

namespace app\adminapi\validate\contract;

use core\base\BaseValidate;
use think\facade\Db;

class ContractValidate extends BaseValidate
{


    protected $rule = [
        'name' =>  'require|unique:contract,delete_time=0',
        'customer_id' => 'require',
        'order_user_id' => 'require',
        'contacts_id' => 'require|checkContacts',
        'sales_manager_id' => 'require',
        'business_id' => 'checkBusiness',
        'start_time' => 'require',
        'end_time' => 'require|checkTime',
        'signing_time' => 'require',
        'status' => 'require',
        'money'  =>  'require|validNumber|between:1,99999999.99',
        'product' => 'array',
        'product.*.price' => 'requireWith:product|validNumber',
        'product.*.sale_price' => 'requireWith:product|validNumber',
        'product.*.number' => 'requireWith:product|validNumber',
        'product.*.unit' => 'requireWith:product',
        'product.*.category' => 'requireWith:product',
        'product.*.product_id' => 'requireWith:product',
    ];


    public $field = [
        'name' => '合同名称',
        'status' => '合同状态',
        'business_id' => '商机',
        'customer_id' => '客户',
        'contacts_id' => '客户签约人',
        'order_user_id' => '公司签约人',
        'sales_manager_id' => '销售主管',
        'start_time' => '合同开始时间',
        'end_time' => '合同到期时间',
        'signing_time' => '合同签约时间'
    ];


    protected $message = [
        'name.unique' => '合同名称已存在,请修改后重新提交',
        'money.validNumber' => '合同价格请输入有效的数字',
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


    /**
     * 验证结束时间要大于开始时间
     * @param string $value 验证内容
     * @return  bool
     */
    public function checkTime($value, $rule, $data, $field)
    {
        if (str2time_date($value) < str2time_date($data['start_time'])) {
            return '合同到期时间必须大于开始时间';
        }
        return true;
    }



    /**
     * 验证商机是否属于所选客户
     * @param string $value 验证内容
     * @return  bool
     */
    public function checkBusiness($value, $rule, $data, $field)
    {
        $customer_id = $data['customer_id'];
        if ($value) {
            $business = Db::name('business')->where('id', $value)->where('customer_id', $customer_id)->value('id');
            return $business ?  true : '商机不属于所选客户';
        } else {
            return true;
        }
    }


    /**
     * 验证客户签约人是否属于所选客户
     * @param string $value 验证内容
     * @return  bool
     */
    public function checkContacts($value, $rule, $data, $field)
    {
        $customer_id = $data['customer_id'];
        if ($value) {
            $contacts = Db::name('contacts')->where('id', $value)->where('customer_id', $customer_id)->value('id');
            return $contacts ?  true : '客户签约人不属于所选客户';
        } else {
            return true;
        }
    }
}
