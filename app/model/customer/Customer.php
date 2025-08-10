<?php

namespace app\model\customer;

use core\base\BaseModel;
use think\model\concern\SoftDelete;
use app\model\system\User;
use core\facade\Util;

class Customer extends BaseModel
{


    use SoftDelete;

    //开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    //自动写入时间戳字段
    protected $createTime = 'create_time';

    //软删除字段
    protected $deleteTime = 'delete_time';

    //软删除默认值
    protected $defaultSoftDelete = 0;

    //定义类型转换
    protected $type = [
        'create_time'  =>  'timestamp:Y年m月d',
        'next_time'  =>  'timestamp:Y年m月d'
    ];

    // ===================== 获取器 =================
    //客户级别
    public function getLevelTextAttr($value, $data)
    {
        return get_dict_map('level', $data['level']);
    }

    //客户来源
    public function getSourceTextAttr($value, $data)
    {
        return get_dict_map('source', $data['source']);
    }

    //行业
    public function getIndustryTextAttr($value, $data)
    {
         return get_dict_map('industry', $data['industry']);
    }

    //最后跟进时间
    public function getLastTimeTextAttr($value, $data)
    {
        return time_format($data['last_time']) ?? '-';
    }

    //成交状态
    public function getDealStatusTextAttr($value, $data)
    {
        return get_dict_map('cust_deal_status', $data['deal_status']);
    }

    //成交状态标签
    public function getDealStatusTagAttr($value, $data)
    {
        return get_dict_map('cust_deal_status', $data['deal_status'], '-', true);
    }

    //分配状态
    public function getAllotStatusTextAttr($value, $data)
    {
        $dict = [0 => '未分配', 1 => '已分配'];
        return $dict[$data['is_receive']] ?? '-';
    }

    //定义用户相对关联
    public function user()
    {
        return $this->belongsTo(User::class, 'owner_user_id', 'id')->bind(['realname']);
    }

    //定义用户相对关联
    public function creator()
    {
        return $this->belongsTo(User::class, 'create_user_id', 'id')->field(['id,realname']);
    }

    //===================== 搜索器 =================
    //关键词
    public function searchKeyAttr($query, $value)
    {
        $query->whereLike('name|mobile', $value);
    }

    //地址
    public function searchAddrAttr($query, $value)
    {
        $query->whereLike('addr', $value);
    }

    //最后跟进
    public function searchLastRecordAttr($query, $value)
    {
        $query->whereLike('last_record', $value);
    }

    //来源
    public function searchSourceAttr($query, $value)
    {
        $query->where('source', $value);
    }

    //客户级别
    public function searchLevelAttr($query, $value)
    {
        $query->where('level', $value);
    }

    //行业
    public function searchIndustryAttr($query, $value)
    {
        $query->where('industry', $value);
    }

    //负责人
    public function searchOwnerUserIdAttr($query, $value)
    {
        $query->where('owner_user_id', $value);
    }

    //成交状态
    public function searchDealStatusAttr($query, $value)
    {
        $query->where('deal_status', $value);
    }

    //分配状态
    public function searchIsReceiveAttr($query, $value)
    {
        $query->where('is_receive', $value);
    }

    //分配、领取时间
    public function searchReceiveTimeAttr($query, $value)
    {
        $query->whereTime('receive_time', 'between', between_time($value));
    }

    //成交时间
    public function searchDealTimeAttr($query, $value)
    {
        $query->whereTime('deal_time', 'between', between_time($value));
    }

    //添加时间
    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereTime('create_time', 'between', between_time($value));
    }

    //地址修改器
    public function setAddrAttr($value)
    {
        $this->set('gps', Util::addrToGps($value));
        return $value;
    }
}
