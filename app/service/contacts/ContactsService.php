<?php

namespace app\service\contacts;

use core\base\BaseService;
use app\model\contacts\Contacts;
use app\model\customer\Customer;
use core\facade\Util;
use core\exception\FailedException;
use app\model\system\User;
use think\facade\Db;

class ContactsService extends BaseService
{


    public function __construct(Contacts $model)
    {
        $this->model = $model;
    }



    /**
     * 获取列表
     * @return array
     */
    public function getList()
    {
        $addFiled = ['decision_text', 'gender_text', 'last_time_text','job_text'];
        $data = $this->model->search()->order('id', 'desc')->append($addFiled)->with(['user', 'customer'])->paginate();
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
        $contacts_id =  $this->model->storeBy($data);
        if ($contacts_id) {
            action_log('contacts', $contacts_id, '新增', '新增联系人『' . $data['name'] . '』');
        }
        return $contacts_id;
    }


    /**
     * 更新
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $contactsData = $this->model->findOrFail($id)->toArray();
        $changed = Util::getChangedFields($contactsData, $data);
        [$result, $message] = $this->model->checkDataAuth($contactsData['owner_user_id'], $contactsData['name']);
        if (!$result) throw new FailedException($message);
        $result = $this->model->updateBy($id, $data);
        if ($result && !empty($changed)) {
            $logContent = '更新联系人『' . ($data['name'] ?? $contactsData['name']) . '』，变更字段：';
            $logContent .=  Util::formatChangedFields($changed);
            // 写入日志
            action_log('contacts', $id, '更新', $logContent);
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
        $data = $this->model->with(['user', 'customer'])->append(['primary_text', 'gender_text', 'last_time_text','job_text'])->findOrFail($id);
        [$result, $message] = $this->model->checkDataAuth($data['owner_user_id'], $data['name']);
        if (!$result) throw new FailedException($message);
        return $data;
    }


    /**
     * 更换负责人
     *
     * @param  array  $id
     * @return bool
     */
    public function changeOwnerUser(array $contacts_id, $owner_user_id)
    {
        $contactsData = $this->model->whereIn('id', $contacts_id)->select()->toArray();
        if (empty($contactsData)) {
            throw new FailedException('更换失败，联系人数据为空');
        }
        $authIds = $this->model->getUserIdsByPermissions();
        $realname = User::getRealNameById($owner_user_id);
        $errorInfo = [];
        foreach ($contactsData as $contacts) {
            // 如果已经是该负责人，跳过
            if ($contacts['owner_user_id'] == $owner_user_id) {
                continue;
            }
            //权限判断
            if (!empty($authIds) && !in_array($contacts['owner_user_id'], $authIds)) {
                $errorInfo[] = '联系人『' . $contacts['name'] . '』更换负责人失败，没有权限；';
                continue;
            }
            // 更新
            $data['owner_user_id'] = $owner_user_id;
            $result = $this->model->where('id', $contacts['id'])->update($data);
            if (!$result) {
                $errorInfo[] = '联系人『' . $contacts['name'] . '』更换负责人失败，数据出错；';
                continue;
            }
            // 记录日志
            action_log('contacts', $contacts['id'], '更换负责人', '联系人『' . $contacts['name'] . '』更换负责人为' . $realname);
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
     * @param  $id  id主键
     * @return bool
     */
    public function delete($contacts_id)
    {
        $authIds = $this->model->getUserIdsByPermissions();
        //错误信息
        $errorInfo = [];
        foreach ($contacts_id as $v) {
            $contacts = $this->model->find($v);
            if (!$contacts) {
                $errorInfo[] = '联系人ID『' . $v . '』数据不存在；';
                continue;
            }
            //权限判断
            if (!empty($authIds) && !in_array($contacts->owner_user_id, $authIds)) {
                $errorInfo[] = '联系人『' . $contacts->getAttr('name') . '』删除失败，没有权限；';
                continue;
            }
            //存在合同无法删除
            $contract = Db::name('contract')->where(['contacts_id' => $v])->find();
            if ($contract) {
                $errorInfo[] = '联系人『' . $contacts->getAttr('name') . '』删除失败,存在合同无法删除；';
                continue;
            }
            $result = $this->model->deleteBy($v);

            if ($result) {
                action_log('contacts', $v, '删除', '删除联系人『' . $contacts->getAttr('name') . '』');
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
