<?php

namespace app\service\clues;

use core\base\BaseService;
use app\model\clues\Clues;
use app\model\system\User;
use app\model\record\Record;
use app\model\customer\Customer;
use app\service\record\RecordService;
use app\model\action_log\ActionLog;
use core\facade\Util;
use core\exception\FailedException;
use app\adminapi\validate\customer\CustomerValidate;

class CluesService extends BaseService
{


    public function __construct(Clues $model)
    {
        $this->model = $model;
    }



    /**
     * 获取列表
     * @return array
     */
    public function getList(array $param)
    {
        $addFiled = ['source_text', 'industry_text', 'level_text', 'last_time_text'];
        return $this->model->dataRange('owner_user_id')->search($param)->order('id', 'desc')->append($addFiled)->with(['user'])->paginate();
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
        $clues_id =  $this->model->storeBy($data);
        if ($clues_id) {
            action_log('clues', $clues_id, '新增', '新增线索『' . $data['name'] . '』');
        }
        return $clues_id;
    }




    /**
     * 修改
     * @param int   $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $clueData = $this->model->findOrFail($id)->toArray();
        $changed = Util::getChangedFields($clueData, $data);
        [$result, $message] = $this->model->checkDataAuth($clueData['owner_user_id'], $clueData['name']);
        if (!$result) throw new FailedException($message);
        $result = $this->model->updateBy($id, $data);
        if ($result && !empty($changed)) {
            $logContent = '更新线索『' . ($data['name'] ?? $clueData['name']) . '』，变更字段：';
            $logContent .=  Util::formatChangedFields($changed);
            // 写入日志
            action_log('clues', $id, '更新', $logContent);
        }
        return $result;
    }


    /**
     * 获取详情数据
     *
     * @param  int  $id
     * @return array
     */
    public function read($id)
    {
        $data = $this->model->with(['user'])->append(['source_text', 'industry_text', 'level_text', 'last_time_text'])->findOrFail($id);
        [$result, $message] = $this->model->checkDataAuth($data['owner_user_id'], $data['name']);
        if (!$result) throw new FailedException($message);
        return $data;
    }


    /**
     * 显示编辑资源表单页
     *
     * @param  int  $id
     * @return array
     */
    public function edit($id)
    {
        $data = $this->model->findOrFail($id);
        [$result, $message] = $this->model->checkDataAuth($data['owner_user_id'], $data['name']);
        if (!$result) throw new FailedException($message);
        return $data;
    }


    /**
     * 分配线索
     *
     * @param array $clues_id 线索id数组
     * @param int   $owner_user_id 新负责人ID
     * @return bool
     */
    public function allocation(array $clues_id, int $owner_user_id): bool
    {
        $cluesData = $this->model->whereIn('id', $clues_id)->select()->toArray();
        if (empty($cluesData)) {
            throw new FailedException('分配失败，线索数据为空');
        }
        $authIds = $this->model->getUserIdsByPermissions();
        $realname = User::getRealNameById($owner_user_id);
        $errorInfo = [];
        foreach ($cluesData as $clue) {
            $clueId = $clue['id'];
            // 如果已经是该负责人，跳过
            if ($clue['owner_user_id'] == $owner_user_id) {
                continue;
            }
            //权限判断
            if (!empty($authIds) && !in_array($clue['owner_user_id'], $authIds)) {
                $errorInfo[] = '线索『' . $clue['name'] . '』分配失败，没有权限；';
                continue;
            }
            // 更新
            $result = $this->model->where('id', $clueId)->update(['owner_user_id' => $owner_user_id]);
            if (!$result) {
                $errorInfo[] = '线索『' . $clue['name'] . '』分配失败，数据出错；';
                continue;
            }
            // 记录日志
            action_log('clues', $clueId, '分配', '分配线索『' . $clue['name'] . '』给' . $realname);
        }
        if (empty($errorInfo)) {
            return true;
        } else {
            $this->error = $errorInfo;
            return false;
        }
    }



    /**
     * 转换成客户
     *
     * @param  int  $id
     * @return bool
     */
    public function transform($id)
    {

        $data = $this->model->findOrFail($id)->toArray();
        [$result, $message] = $this->model->checkDataAuth($data['owner_user_id'], $data['name']);
        if (!$result) throw new FailedException($message);
        $customer = new Customer;
        $recordService = app()->make(RecordService::class);
        //是否已转换
        if ($data['is_transform'] == 1) {
            throw new FailedException('该线索已转换成客户，请勿重复操作');
        }
        //验证客户
        validate(customerValidate::class)->check($data);
        $this->startTrans();
        try {
            //新增客户
            $data['create_time'] = time();
            $customer_id = $customer->storeBy($data);
            if ($customer_id) {
                //更新线索已转化
                $data['customer_id'] = $customer_id;
                $data['is_transform'] = 1; //已转换
                $this->model->where('id', $data['id'])->update($data);
                //同步跟进记录到客户
                $recordService->syncRecord('clues', $data['id'], 'customer', $customer_id);
                //写入线索操作日志
                action_log('clues', $data['id'], '转换', '转换线索『' . $data['name'] . '』为客户');
                //写入客户操作日志
                action_log('customer', $customer_id, '转换', '由线索『' . $data['name'] . '』转换为客户');
            }
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }
        return true;
    }



    /**
     * 删除
     *
     * @return bool
     */
    public function delete($ids)
    {
        $cluesData = $this->model->whereIn('id', $ids)->select()->toArray();
        if (empty($cluesData)) {
            throw new FailedException('删除失败，线索数据为空');
        }
        $authIds = $this->model->getUserIdsByPermissions();
        $recordService = app()->make(RecordService::class);
        $delIds = [];
        $errorInfo = [];
        foreach ($cluesData as $clue) {
            //权限判断
            if (!empty($authIds) && !in_array($clue['owner_user_id'], $authIds)) {
                $errorInfo[] = '线索『' . $clue['name'] . '』删除失败，没有权限删除该线索；';
                continue;
            }
            $delIds[] = $clue['id'];
        }
        if (empty($delIds)) {
            $this->error = $errorInfo;
            return false;
        }
        $this->startTrans();
        try {
            //删除线索
            $this->model->whereIn('id', $delIds)->delete();
            //删除跟进
            foreach ($ids as $v) {
                $recordService->deleteByType('clues', $v);
            }
            //删除日志
            ActionLog::whereIn('data_id', $delIds)->where('type', Record::CLUE_TYPE)->delete();

            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            $errorInfo[] = '线索『' . $clue['name'] . '』删除失败；';
        }

        if (empty($errorInfo)) {
            return true;
        } else {
            $this->error = $errorInfo;
            return false;
        }
    }
}
