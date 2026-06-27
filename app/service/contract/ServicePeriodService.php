<?php

namespace app\service\contract;

use core\base\BaseService;
use app\model\contract\ServicePeriod;

class ServicePeriodService extends BaseService
{
    /**
     * 初始化服务周期服务
     *
     * @param ServicePeriod $model 服务周期模型
     * @return void
     */
    public function __construct(ServicePeriod $model)
    {
        $this->model = $model;
    }

    /**
     * 获取服务周期列表
     *
     * @return mixed
     */
    public function getList()
    {
        return $this->model
            ->search()
            ->with(['contractProduct', 'ownerUser'])
            ->append(['status_text'])
            ->order('period_no', 'asc')
            ->order('id', 'desc')
            ->paginate();
    }

    /**
     * 更新服务周期
     *
     * @param int $id 周期ID
     * @param array $data 周期数据
     * @return bool
     */
    public function update($id, array $data)
    {
        if (($data['status'] ?? null) == 2 && empty($data['finish_time'])) {
            // 标记完结时自动记录完结时间，避免月度执行记录缺失闭环时间。
            $data['finish_time'] = time();
        }
        return $this->model->updateBy($id, $data);
    }
}
