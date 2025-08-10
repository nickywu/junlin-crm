<?php

namespace app\model\clues;

use core\base\BaseModel;
use app\model\system\User;
use core\facade\Util;
class Clues extends BaseModel
{
 
    //开启自动写入时间戳
    protected $autoWriteTimestamp = true;
    //自动写入时间戳字段
    protected $createTime = 'create_time';
    //关闭自动写入update_time字段
    protected $updateTime = false;
    //定义类型转换
    protected $type = [
        'create_time'  =>  'timestamp:Y年m月d'
    ];

    //定义用户相对关联
    public function user(){
        return $this->belongsTo(User::class,'owner_user_id','id')->bind(['realname']);
    }

    //地址修改器
    public function setAddrAttr($value){
       $this->set('gps', Util::addrToGps($value));
       return $value;
    }

    // ===================== 获取器 =================
    //线索级别
    public function getLevelTextAttr($value, $data)
    {
        return get_dict_map('level',$data['level']);
    }

    //线索行业
    public function getSourceTextAttr($value, $data)
    {
        return get_dict_map('source',$data['source']);
    }

    //行业
    public function getIndustryTextAttr($value, $data)
    {
         return get_dict_map('industry',$data['industry']);
    }

    //最后跟进时间
    public function getLastTimeTextAttr($value, $data)
    {
        return time_format($data['last_time']) ?? '-';
    }

    //===================== 搜索器 =================

    //关键词
    public function searchKeyAttr($query, $value){
        $query->whereLike('name|mobile',$value);
    }
    
    //线索地址
    public function searchAddrAttr($query, $value){
        $query->whereLike('addr',$value);
    }

    //最后跟进
    public function searchLastRecordAttr($query, $value){
        $query->whereLike('last_record',$value);
    }

    //线索来源
    public function searchSourceAttr($query, $value){
        $query->where('source',$value);
    }

    //线索级别
    public function searchLevelAttr($query, $value){
        $query->where('level',$value);
    }

    //线索行业
    public function searchIndustryAttr($query, $value){
        $query->where('industry',$value);
    }

    //负责人
    public function searchOwnerUserIdAttr($query, $value){
        $query->where('owner_user_id',$value);
    }

    //转换状态
    public function searchIsTransformAttr($query, $value){
        $query->where('is_transform',$value);
    }
    
}
