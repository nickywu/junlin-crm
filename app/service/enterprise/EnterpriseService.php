<?php

namespace app\service\enterprise;

use core\base\BaseService;
use app\model\enterprise\Enterprise;
use app\model\customer\Customer;
use core\facade\Util;
use core\exception\FailedException;

class EnterpriseService extends BaseService
{

    public function __construct(Enterprise $model)
    {
        $this->model = $model;
    }

    /**
     * 获取列表（支持按关键词、客户、纳税性质等搜索）
     * @return array
     */
    public function getList()
    {
        $addFiled = ['tax_nature_text'];
        $data = $this->model
            ->dataRange('owner_user_id')
            ->search()
            ->order('id', 'desc')
            ->append($addFiled)
            ->with(['user', 'customer'])
            ->paginate();
        return $data;
    }

    /**
     * 保存企业信息
     * @param array $data
     * @return int
     */
    public function save(array $data)
    {
        $data['create_user_id'] = request()->uid();
        $data['owner_user_id'] = request()->uid();
        $enterprise_id = $this->model->storeBy($data);
        if ($enterprise_id) {
            action_log('enterprise', $enterprise_id, '新增', '新增企业『' . $data['name'] . '』');
        }
        return $enterprise_id;
    }

    /**
     * 更新企业信息
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $enterpriseData = $this->model->findOrFail($id)->toArray();
        $changed = Util::getChangedFields($enterpriseData, $data);
        [$result, $message] = $this->model->checkDataAuth($enterpriseData['owner_user_id'], $enterpriseData['name']);
        if (!$result) throw new FailedException($message);
        $result = $this->model->updateBy($id, $data);
        if ($result && !empty($changed)) {
            $logContent = '更新企业『' . ($data['name'] ?? $enterpriseData['name']) . '』，变更字段：';
            $logContent .=  Util::formatChangedFields($changed);
            // 写入日志
            action_log('enterprise', $id, '更新', $logContent);
        }
        return $result;
    }

    /**
     * 获取编辑的数据
     * @param  int  $id
     * @return array
     */
    public function edit($id)
    {
        $data = $this->model->findOrFail($id);
        // 附带关联客户信息，用于前端表格选择器回显
        $data['customer'] = Customer::field('id,name')->find($data->customer_id);
        [$result, $message] = $this->model->checkDataAuth($data['owner_user_id'], $data['name']);
        if (!$result) throw new FailedException($message);
        return $data;
    }

    /**
     * 获取详情的数据
     * @param  int  $id
     * @return array
     */
    public function read($id)
    {
        $data = $this->model
            ->with(['user', 'customer'])
            ->append(['tax_nature_text'])
            ->findOrFail($id);
        [$result, $message] = $this->model->checkDataAuth($data['owner_user_id'], $data['name']);
        if (!$result) throw new FailedException($message);
        return $data;
    }

    /**
     * 删除企业
     * @param  array $id  id数组
     * @return bool
     */
    public function delete($enterprise_id)
    {
        $authIds = $this->model->getUserIdsByPermissions();
        //错误信息
        $errorInfo = [];
        foreach ($enterprise_id as $v) {
            $enterprise = $this->model->find($v);
            if (!$enterprise) {
                $errorInfo[] = '企业ID『' . $v . '』数据不存在；';
                continue;
            }
            //权限判断
            if (!empty($authIds) && !in_array($enterprise->owner_user_id, $authIds)) {
                $errorInfo[] = '企业『' . $enterprise->getAttr('name') . '』删除失败，没有权限；';
                continue;
            }
            $result = $this->model->deleteBy($v);
            if ($result) {
                action_log('enterprise', $v, '删除', '删除企业『' . $enterprise->getAttr('name') . '』');
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
