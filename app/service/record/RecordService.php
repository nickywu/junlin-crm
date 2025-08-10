<?php

namespace app\service\record;

use core\base\BaseService;
use app\model\record\Record;
use think\facade\Db;

class RecordService extends BaseService
{


    public function __construct(Record $model)
    {
        $this->model = $model;
    }


    /**
     * 获取列表
     * @param   string $record_type 跟进类型
     * @param   string|int $type_id 类型主键
     * @return  array
     */
    public function getList($record_type, $type_id)
    {
        $data = $this->model->dataQuery($record_type, $type_id)->search()->order('id', 'desc')->append(['category_text'])->with(['user', 'files'])->select();
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
        //开启事务
        $this->startTrans();
        try {
            $record_id = $this->model->storeBy($data);
            $fileIds  = $data['file'] ?? [];
            //更新最后一次跟进记录
            if ($record_id) {
                $this->updateLastRecord($data['data_id'], $data['content'], $data['record_type']);
            }
            //写入文件
            if (!empty($fileIds)) {
                //关联写入
                $this->model->find($record_id)->files()->attach($fileIds);
            }
            $this->commit();  //提交事务
        } catch (\Exception $e) {
            $this->rollback();  //回滚事务
            return false;
        }
        return true;
    }

    /**
     * 更新最后一次跟进记录
     * @param number $id 数据主键
     * @param string $content 跟进内容
     * @param string $record_type  类型
     */
    private function updateLastRecord($data_id, $content, $type)
    {
        $model = null;
        $typeValue = Record::getTypeValue($type);
        if (!is_null($typeValue)) {
            $model = Db::name($type);
            $data['last_time'] = time();
            $data['last_record'] = $content;
            $data['last_user_id'] = request()->uid();
            $model->where('id', $data_id)->update($data);
        }
    }

    /**
     * 删除
     * @param  $id  id主键
     * @return bool
     */
    public function delete($id)
    {
        try {
            $this->transaction(function () use ($id) {
                $this->model->deleteBy($id);
                //删除跟进关联的文件
                $this->model->find($id)->files()->detach();
            });
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 同步其他模块的跟进
     * @param string $old_type  旧类型
     * @param int $old_id    旧类型数据主键
     * @param string $new_type  新类型
     * @param int  $new_id    新id
     */
    public function syncRecord($old_type, $old_id, $new_type, $new_id)
    {
        $record = $this->model->dataQuery($old_type, $old_id)->with(['files'])->order('id', 'asc')->select()->toArray();
        $files = [];
        foreach ($record as $value) {
            $value['record_type'] = $new_type;
            $value['data_id'] = $new_id;
            $record_id = $this->model->createBy($value);
            //同步跟进关联的文件
            foreach ($value['files'] as $k => $v) {
                $files[$k]['file_id'] = $v['pivot']['file_id'];
                $files[$k]['record_id'] = $record_id;
            }
        }
        if (!empty($files)) Db::name('record_file')->insertAll($files);
    }


    /**
     * 根据类型删除数据
     * @param strnig $type  类型
     * @param int $data_id 数据主键
     * @return bool
     */
    public function deleteByType($type, $data_id)
    {

        $record_id = $this->model->dataQuery($type, $data_id)->column('id');

        if (empty($record_id)) return false;

        //删除跟进
        $this->model->dataQuery($type, $data_id)->delete();

        //文件id
        $fileIds = Db::name('record_file')->whereIn('record_id', $record_id)->column('file_id');

        //删除跟进关联的文件
        $result = Db::name('record_file')->whereIn('record_id', $record_id)->delete();

        if ($result && $fileIds) {
            $fileList = Db::name('file')->whereIn('id', $fileIds)->field('path')->select();
            foreach ($fileList as $v) {
                $filePath = config('filesystem.disks.local.root') . DIRECTORY_SEPARATOR .  $v['path'];
                //删除文件
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }
        return true;
    }
}
