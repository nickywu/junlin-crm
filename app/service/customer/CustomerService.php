<?php

namespace app\service\customer;

use core\base\BaseService;
use app\model\customer\Customer;
use core\exception\FailedException;
use app\model\system\User;
use think\facade\Db;
use core\facade\Util;

class CustomerService extends BaseService
{


    public function __construct(Customer $model)
    {
        $this->model = $model;
    }



    /**
     * 获取列表
     * @return array
     */
    public function getList()
    {
        $addFiled = ['source_text', 'industry_text', 'level_text', 'last_time_text', 'deal_status_tag'];
        $data =  $this->model->dataRange('owner_user_id')->search()->order('id', 'desc')->append($addFiled)->with(['user', 'creator'])->paginate();
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
        $data['owner_user_id'] = request()->uid();
        $data['is_receive'] = 1;
        $data['receive_time'] = time();
        $customer_id = $this->model->storeBy($data);
        if ($customer_id) {
            action_log('customer', $customer_id, '新增', '新增客户『' . $data['name'] . '』');
        }
        return $customer_id;
    }


    /**
     * 更新
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $customerData = $this->model->findOrFail($id)->toArray();
        $changed = Util::getChangedFields($customerData, $data);
        [$result, $message] = $this->model->checkDataAuth($customerData['owner_user_id'], $customerData['name']);
        if (!$result) throw new FailedException($message);
        $result = $this->model->updateBy($id, $data);
        if ($result && !empty($changed)) {
            $logContent = '更新客户『' . ($data['name'] ?? $customerData['name']) . '』，变更字段：';
            $logContent .=  Util::formatChangedFields($changed);
            // 写入日志
            action_log('customer', $id, '更新', $logContent);
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
        $data = $this->model->find($id);
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
        $addFiled = ['source_text', 'industry_text', 'level_text', 'last_time_text', 'deal_status_text'];
        $data = $this->model->with(['user'])->append($addFiled)->findOrFail($id);
        [$result, $message] = $this->model->checkDataAuth($data['owner_user_id'], $data['name']);
        if (!$result) throw new FailedException($message);
        return $data;
    }


    /**
     * 分配客户
     *
     * @param array $customer_id 客户id数组
     * @param int   $owner_user_id 新负责人ID
     * @return bool 
     */
    public function allocation(array $customer_id, int $owner_user_id): bool
    {
        $customerData = $this->model->whereIn('id', $customer_id)->select()->toArray();
        if (empty($customerData)) {
            throw new FailedException('分配失败，客户数据为空');
        }
        $authIds = $this->model->getUserIdsByPermissions();
        $realname = User::getRealNameById($owner_user_id);
        $errorInfo = [];
        foreach ($customerData as $customer) {
            $customerId = $customer['id'];
            // 如果已经是该负责人，跳过
            if ($customer['owner_user_id'] == $owner_user_id) {
                continue;
            }
            //权限判断
            if (!empty($authIds) && !in_array($customer['owner_user_id'], $authIds)) {
                $errorInfo[] = '客户『' . $customer['name'] . '』分配失败，没有权限分配该客户；';
                continue;
            }
            // 更新
            $data['owner_user_id'] = $owner_user_id;
            $data['is_receive'] = 1;
            $data['receive_time'] = time();
            $result = $this->model->where('id', $customerId)->inc('transfer_count')->update($data);
            if (!$result) {
                $errorInfo[] = '客户『' . $customer['name'] . '』分配失败，数据出错；';
                continue;
            }
            action_log('customer', $customerId, '分配', '分配客户『' . $customer['name'] . '』给' . $realname);
        }
        if (empty($errorInfo)) {
            return true;
        } else {
            $this->error = $errorInfo;
            return false;
        }
    }




    /**
     * 更改成交状态
     *
     * @param  array  $id
     * @return bool
     */
    public function changeDealStatus($id, $status)
    {
        $authIds = $this->model->getUserIdsByPermissions();
        $data['deal_status'] = $status;
        $data['deal_time'] = time();
        $dict_cache = get_dict_cache('cust_deal_status');
        $customerData = $this->model->whereIn('id', $id)->select()->toArray();
        //错误信息
        $errorInfo = [];
        foreach ($customerData as $value) {
            //权限判断
            if (!empty($authIds) && !in_array($value['owner_user_id'], $authIds)) {
                $errorInfo[] = '客户『' . $value['name'] . '』更改失败，没有权限；';
                continue;
            }
            $result = $this->model->where('id', $value['id'])->update($data);
            if (!$result) {
                $errorInfo[] = '客户『' . $value['name'] . '』更改失败，数据出错；';
                continue;
            }
            action_log('customer', $value['id'], '更改成交状态', "更改客户『{$value['name']}』成交状态为『{$dict_cache[$status]}』");
        }
        if (empty($errorInfo)) {
            return true;
        } else {
            $this->error = $errorInfo;
            return false;
        }
    }




    /**
     * 删除
     * @param  array $id  id主键
     * @return bool
     */
    public function delete($customer_id)
    {
        $authIds = $this->model->getUserIdsByPermissions();
        //错误信息
        $errorInfo = [];
        foreach ($customer_id as $v) {
            $customer = $this->model->find($v);
            if (!$customer) {
                $errorInfo[] = '客户ID『' . $v . '』数据不存在；';
                continue;
            }
            //权限判断
            if (!empty($authIds) && !in_array($customer->owner_user_id, $authIds)) {
                $errorInfo[] = '客户『' . $customer->getAttr('name') . '』删除失败，没有权限删除该客户；';
                continue;
            }
            //存在联系人无法删除
            $contacts = Db::name('contacts')->where(['customer_id' => $v])->find();
            if ($contacts) {
                $errorInfo[] = '客户『' . $customer->getAttr('name') . '』删除失败,存在联系人无法删除；';
                continue;
            }
            //存在合同无法删除
            $contract = Db::name('contract')->where(['customer_id' => $v])->find();
            if ($contract) {
                $errorInfo[] = '客户『' . $customer->getAttr('name') . '』删除失败,存在合同无法删除；';
                continue;
            }
            //存在商机无法删除
            $business = Db::name('business')->where(['customer_id' => $v])->find();
            if ($business) {
                $errorInfo[] = '客户『' . $customer->getAttr('name') . '』删除失败,存在商机无法删除；';
                continue;
            }
            //删除
            $result = $this->model->deleteBy($v);
            if (!$result) {
                $errorInfo[] = '客户『' . $customer->getAttr('name') . '』删除失败,数据出错；';
                continue;
            }
            action_log('customer', $v, '删除', '删除客户『' . $customer->getAttr('name') . '』');
        }
        if (empty($errorInfo)) {
            return true;
        } else {
            $this->error = $errorInfo;
            return false;
        }
    }
}
