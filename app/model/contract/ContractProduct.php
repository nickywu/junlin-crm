<?php

namespace app\model\contract;

use core\base\BaseModel;
use  app\model\product\Product;

class ContractProduct extends BaseModel
{


    //定义产品相对关联
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')->bind(['name']);
    }
}
