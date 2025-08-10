<?php

namespace app\adminapi\validate\record;

use core\base\BaseValidate;
use app\model\record\Record;

class RecordValidate extends BaseValidate
{

    protected $rule = [
        'record_type' => 'require|checkRecordtType',
        'data_id' => 'require',
        'content' => 'require|max:1000'
    ];


    public $field = [
        'record_type' => '跟进类型',
        'data_id' => '数据主键',
        'content' => '跟进内容'
    ];


    /**
     * 验证跟进类型参数
     * @param string $value 验证内容
     * @return  bool
     */
    public function checkRecordtType($value)
    {
        $types = Record::getTypeValue($value);
        if (is_null($types)) {
            return '跟进类型参数错误';
        }
        return true;
    }
}
