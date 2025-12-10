<?php

namespace app\model\product;

use core\base\BaseModel;
use think\model\concern\SoftDelete;
use app\model\system\User;
use think\Model;
use InvalidArgumentException;

class Product extends BaseModel
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


    //定义用户相对关联
    public function user()
    {
        return $this->belongsTo(User::class, 'create_user_id', 'id')->bind(['realname']);
    }

    //产品类别
    public function getCategoryTextAttr($value, $data)
    {
        return get_dict_map('product_category', $data['category']);
    }

    //产品单位
    public function getUnitTextAttr($value, $data)
    {
        return get_dict_map('product_unit', $data['unit']);
    }


    //关键词
    public function searchKeyAttr($query, $value)
    {
        $query->whereLike('name|code', $value);
    }

    //产品类别
    public function searchCategoryAttr($query, $value)
    {
        $query->where('category', $value);
    }

    //添加时间
    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereTime('create_time', 'between', between_time($value));
    }


    //创建人
    public function searchCreateUserIdAttr($query, $value)
    {
        $query->where('create_user_id', $value);
    }

    //产品单位
    public function searchUnitAttr($query, $value)
    {
        $query->where('unit', $value);
    }



    /**
     * 同步关联产品数据
     *
     * @param string $modelClass 模型类的完整命名空间
     * @param string $foreignKey 外键字段名
     * @param mixed $pk 主键值
     * @param array $productData 输入数据列表
     *
     * @throws InvalidArgumentException 
     */
    public static function syncRelatedRecords(string $modelClass, string $foreignKey, $pk, array $productData): void
    {
        // 验证类是否存在且是 Model 子类
        if (!class_exists($modelClass)) {
            throw new InvalidArgumentException("Class {$modelClass} does not exist.");
        }

        if (!is_subclass_of($modelClass, Model::class)) {
            throw new InvalidArgumentException("Class {$modelClass} must be a subclass of " . Model::class);
        }

        /** @var Model $modelInstance */
        $modelInstance = new $modelClass();

        // 数据为空：删除所有关联记录
        if (empty($productData)) {
            $modelInstance->where($foreignKey, $pk)->delete();
            return;
        }

        // 获取需要删除的id
        $existingIds = $modelInstance->where($foreignKey, $pk)->column('id');
        $incomingIds = array_filter(array_column($productData, 'id'));
        $deleteIds = array_diff($existingIds, $incomingIds);

        // 存在删除的id，则删除记录
        if (!empty($deleteIds)) {
            $modelInstance->whereIn('id', $deleteIds)->delete();
        }

        // 更新或新增
        foreach ($productData as $item) {
            if (!empty($item['id'])) {
                // 更新
                $modelInstance->update($item);
            } else {
                // 新增
                $item[$foreignKey] =  $pk;
                $modelInstance->create($item);
            }
        }
    }
}
