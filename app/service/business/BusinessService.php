<?php

namespace app\service\business;

use core\base\BaseService;
use app\model\business\Business;
use app\model\customer\Customer;
use core\exception\FailedException;
use think\facade\Db;
use app\model\business\{BusinessStage, BusinessGroup, BusinessProduct};
use app\model\system\User;
use core\facade\Util;
use app\model\product\Product;

class BusinessService extends BaseService
{


    public function __construct(Business $model)
    {
        $this->model = $model;
    }



    /**
     * 获取列表
     * @return array
     */
    public function getList()
    {
        $addFiled = ['last_time_text', 'end_text'];
        $with = ['user', 'customer', 'business_group', 'business_stage'];
        $data = $this->model
            ->dataRange('owner_user_id')
            ->search()
            ->order('id', 'desc')
            ->append($addFiled)
            ->with($with)->paginate();
        return $data;
    }



    /**
     * 保存
     * @param array $data
     * @return bool
     */
    public function save(array $data)
    {
        $data['create_user_id'] = request()->uid();
        $data['owner_user_id'] = request()->uid();
        $product = $data['product'] ?? [];
        $this->startTrans();
        try {
            $business_id =  $this->model->storeBy($data);
            if (!empty($product) && $business_id) {
                $business = $this->model->find($business_id);
                // 批量增加关联数据，
                foreach ($product as $key => $value) {
                    //过滤掉id
                    if (!empty($value['id'])) {
                        unset($product[$key]['id']);
                    }
                }
                $business->product()->saveAll($product);
            }
            if ($business_id) {
                action_log('business', $business_id, '新增', '新增商机『' . $data['name'] . '』');
            }
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }
        return true;
    }

    /**
     * 获取商机组列表
     ** @param int $id
     * @return array
     */
    public function getGroupList()
    {
        return BusinessGroup::field('*')->order('id', 'desc')->with(['stage'])->select();
    }


    /**
     * 获取商机阶段
     ** @param int $id
     * @return array
     */
    public function getBusinessStage($id)
    {
        $business = $this->model->find($id)->append(['end_text', 'last_time_text']);
        $stage_data = BusinessStage::where('group_id', $business->group_id)->select();
        return ['business' => $business, 'stage_data' => $stage_data];
    }


    /**
     * 推进商机阶段
     * @param int $id
     * @param int $stage_id
     * @return bool
     */
    public function changeBusinessStage($id, $stage_id)
    {
        $stage_name = BusinessStage::where('id', $stage_id)->value('name');
        $business = $this->model->findOrFail($id);
        [$result, $message] = $this->model->checkDataAuth($business['owner_user_id'], $business['name']);
        if (!$result) throw new FailedException($message);
        if ($business->is_end) {
            throw new FailedException('商机已结束无法推进');
        }
        $result = $this->model->where('id', $id)->update(['stage_id' => $stage_id, 'stage_time' => time()]);
        if ($result) {
            action_log('business', $id, '推进商机', '推进商机『' .  $business->name . '』至『' . $stage_name . '』阶段');
        }
        return  $result;
    }



    /**
     * 结束商机阶段
    * @param int $id
     * @param int $is_end
     * @return bool
     */
    public function endStage($id, $is_end)
    {
        $business = $this->model->field('is_end,name,owner_user_id')->append(['end_text'])->findOrFail($id);
        $end_text = Business::END_STATUS[$is_end] ?? '-';
        [$result, $message] = $this->model->checkDataAuth($business['owner_user_id'], $business['name']);
        if (!$result) throw new FailedException($message);
        if ($business->is_end) {
            throw new FailedException('商机已结束无法推进');
        }
        $result = $this->model->where('id', $id)->update(['is_end' => $is_end]);
        if ($result) {
            action_log('business', $id, '结束商机', '结束商机『' . $business->getAttr('name') . '』至『' . $end_text . '』状态');
        }
        return $result;
    }


    /**
     * 更换负责人
     *
     * @param array $business_id 商机id数组
     * @param int   $owner_user_id 新负责人ID
     * @return bool 
     */
    public function changeOwnerUser(array $business_id, int $owner_user_id): bool
    {
        $businessData = $this->model->whereIn('id', $business_id)->select()->toArray();
        if (empty($businessData)) {
            throw new FailedException('分配失败，商机数据为空');
        }
        $authIds = $this->model->getUserIdsByPermissions();
        $realname = User::getRealNameById($owner_user_id);
        $errorInfo = [];
        foreach ($businessData as $item) {
            $businessId = $item['id'];
            // 如果已经是该负责人，跳过
            if ($item['owner_user_id'] == $owner_user_id) {
                continue;
            }
            //权限判断
            if (!empty($authIds) && !in_array($item['owner_user_id'], $authIds)) {
                $errorInfo[] = '商机『' . $item['name'] . '』更换负责人失败，没有权限；';
                continue;
            }
            // 更新
            $result = $this->model->where('id', $businessId)->update(['owner_user_id' => $owner_user_id]);
            if (!$result) {
                $errorInfo[] = '商机『' . $item['name'] . '』更换负责人失败，数据出错；';
                continue;
            }
            // 记录日志
            action_log('business', $businessId, '更换负责人', '商机『' . $item['name'] . '』更换负责人为' . $realname);
        }
        if (empty($errorInfo)) {
            return true;
        } else {
            $this->error = $errorInfo;
            return false;
        }
    }



    /**
     * 获取商机产品
     *
     * @param int $id
     * @return array
     */
    public function getProduct($id)
    {
        $data = BusinessProduct::where('business_id', $id)->with(['product'])->paginate();
        return $data;
    }



    /**
     * 更新
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $businessData = $this->model->findOrFail($id)->toArray();
        $changed = Util::getChangedFields($businessData, $data, ['product']);
        [$result, $message] = $this->model->checkDataAuth($businessData['owner_user_id'], $businessData['name']);
        if (!$result) throw new FailedException($message);
        $product = $data['product'] ?? [];
        $result = $this->model->updateBy($id, $data);
         Product::syncRelatedRecords(BusinessProduct::class, 'business_id', $id, $product);
        if ($result) {
            $logContent = '更新商机『' . ($data['name'] ?? $businessData['name']) . '』，变更字段：';
            $logContent .=  Util::formatChangedFields($changed);
            // 写入日志
            action_log('business', $id, '更新', $logContent);
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
        $data = $this->model->findOrFail($id);
        $data['customer'] = Customer::field('id,name')->find($data->customer_id);
        $data['product'] = BusinessProduct::where('business_id', $id)->with(['product'])->select();
        [$result, $message] = $this->model->checkDataAuth($data['owner_user_id'], $data['name']);
        if (!$result) throw new FailedException($message);
        return $data;
    }


    /**
     * 获取详情的数据
     *
     * @param  int  $id
     * @return array
     */
    public function read($id)
    {
        $data = $this->model
            ->with(['user'])
            ->append(['last_time_text', 'end_text'])
            ->with(['user', 'customer', 'business_group', 'business_stage'])
            ->findOrFail($id);
        [$result, $message] = $this->model->checkDataAuth($data['owner_user_id'], $data['name']);
        if (!$result) throw new FailedException($message);
        return $data;
    }


    /**
     * 删除
     * @param  $id  id主键
     * @return bool
     */
    public function delete($business_id)
    {
        $authIds = $this->model->getUserIdsByPermissions();
        //错误信息
        $errorInfo = [];
        foreach ($business_id as $v) {
            $business = $this->model->find($v);
            if (!$business) {
                $errorInfo[] = '商机ID『' . $v . '』数据不存在；';
                continue;
            }
            //权限判断
            if (!empty($authIds) && !in_array($business->owner_user_id, $authIds)) {
                $errorInfo[] = '商机『' . $business->getAttr('name') . '』删除失败，没有权限；';
                continue;
            }
            //存在合同无法删除
            $contract = Db::name('contract')->where(['business_id' => $v])->find();
            if ($contract) {
                $errorInfo[] = '商机『' . $business->getAttr('name') . '』删除失败,存在合同无法删除；';
                continue;
            }
            $result = $this->model->deleteBy($v);
            if ($result) {
                action_log('business', $v, '删除', '删除商机『' . $business->getAttr('name') . '』');
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
