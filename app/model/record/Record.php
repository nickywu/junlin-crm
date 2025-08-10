<?php

namespace app\model\record;

use core\base\BaseModel;
use app\model\system\{User, File};

class Record extends BaseModel
{

    //开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    //自动写入时间戳字段
    protected $createTime = 'create_time';

    //定义类型转换
    protected $type = [
        'create_time'  =>  'timestamp:Y/m/d'
    ];

    // 关闭自动写入update_time字段
    protected $updateTime = false;

    // 类型定义
    const CLUE_TYPE     = 1; // 线索
    const CUSTOMER_TYPE = 2; // 客户
    const CONTACT_TYPE  = 3; // 联系人
    const BUSINESS_TYPE = 4; // 商机
    const CONTRACT_TYPE = 5; // 合同
    const PRODUCT_TYPE  = 6; // 产品

    public static array $record_types = [
        'clues'    => self::CLUE_TYPE,
        'customer' => self::CUSTOMER_TYPE,
        'contacts' => self::CONTACT_TYPE,
        'business' => self::BUSINESS_TYPE,
        'contract' => self::CONTRACT_TYPE,
        'product' =>  self::PRODUCT_TYPE,
    ];

    public static function getTypeValue(string $label): ?int
    {
        return self::$record_types[$label] ?? null;
    }
    //模型事件写入前
    public static function onBeforeWrite($model)
    {
        $model->set('record_type', self::getTypeValue($model->record_type));
    }

    //查询范围
    public function scopeDataQuery($query, $record_type, $data_id)
    {
        $map['record_type'] = self::getTypeValue($record_type);
        $map['data_id'] = $data_id;
        $query->where($map);
    }



    /**
     * 跟进记录和文件多对多关联
     * @time 2021年3月30日
     * @return mixed
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'record_file', 'file_id', 'record_id');
    }



    //定义用户相对关联
    public function user()
    {
        return $this->belongsTo(User::class, 'create_user_id', 'id')->bind(['realname']);
    }
}
