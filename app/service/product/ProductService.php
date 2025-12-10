<?php

namespace app\service\product;

use core\base\BaseService;
use app\model\product\Product;
use think\facade\Db;
use core\facade\Util;
use core\exception\FailedException;

class ProductService extends BaseService
{


    public function __construct(Product $model)
    {
        $this->model = $model;
    }



    /**
     * 获取列表
     * @return array
     */
    public function getList()
    {
        $addFiled = ['category_text', 'unit_text'];
        $data = $this->model->search()->order('id', 'desc')->append($addFiled)->with(['user'])->paginate();
        return $data;
    }



    /**
     * 保存
     * @param array $data
     * @return int
     */
    public function save(array $data)
    {
        $data['create_user_id'] = request()->uid();
        $product_id = $this->model->storeBy($data);
        if ($product_id) {
            action_log('product', $product_id, '新增', '新增产品『' . $data['name'] . '』');
        }
        return $product_id;
    }


    /**
     * 更新
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $productData = $this->model->findOrFail($id)->toArray();
        $changed = Util::getChangedFields($productData, $data, ['cover_image','detail_image']);
        $result = $this->model->updateBy($id, $data);
        if ($result && !empty($changed)) {
            $logContent = '更新产品『' . ($data['name'] ?? $productData['name']) . '』，变更字段：';
            $logContent .=  Util::formatChangedFields($changed);
            // 写入日志
            action_log('product', $id, '更新', $logContent);
        }
        return $result;
    }


    /**
     * 获取编辑的数据
     *
     * @param  int  $id
     * @return array
     */
    public function edit($id)
    {
        return $this->model->findOrFail($id);
    }


    /**
     * 获取详情的数据
     *
     * @param  int  $id
     * @return array
     */
    public function read($id)
    {
        return $this->model->with(['user'])->append(['category_text', 'unit_text'])->findOrFail($id);
    }


    /**
     * 删除
     * @param  $id  id主键
     * @return bool
     */
    public function delete($product_id)
    {
        //错误信息
        $errorInfo = [];
        foreach ($product_id as $v) {
            $product = $this->model->find($v);
            if (!$product) {
                $errorInfo[] = '产品ID『' . $v . '』数据不存在；';
                continue;
            }
            //存在合同无法删除
            $contract = Db::name('contract_product')->where(['product_id' => $v])->find();
            if ($contract) {
                $errorInfo[] = '产品『' . $product->getAttr('name') . '』删除失败,存在关联合同无法删除；';
                continue;
            }
            //存在商机无法删除
            $business = Db::name('business_product')->where(['product_id' => $v])->find();
            if ($business) {
                $errorInfo[] = '产品『' . $product->getAttr('name') . '』删除失败,存在关联商机无法删除；';
                continue;
            }
            $result = $this->model->deleteBy($v);
            if ($result) {
                action_log('product', $v, '删除', '删除产品『' . $product->getAttr('name') . '』');
            }
        }
        if (empty($errorInfo)) {
            return true;
        } else {
            $this->error = $errorInfo;
            return false;
        }
    }
}
