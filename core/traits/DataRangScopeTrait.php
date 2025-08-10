<?php
namespace core\traits;
use app\model\system\{User,Role,Department};
/**
 * 数据权限 
 * trait dataRangScopeTrait
 * @package core\traits
 */
trait DataRangScopeTrait
{


    /**
     * 数据范围查询
     *
     * @param string $field
     * @return mixed
     */
    public function dataRange($field = '')
    {
        //超级管理员角色可查看全部
        if (is_super_admin()) {
            return $this;
        }

        if(empty($field)){
            return $this;
        }

        $userIds =  array_unique($this->getUserIdsByPermissions());

        if (empty($userIds)) {
            return $this;
        }

        return $this->whereIn($field, $userIds);
    }



    /**
     * 通过权限获取用户ids
     *
     * @return array
     */
    public function getUserIdsByPermissions()
    {
        $userIds = [];

        $isAll = false;

        $user = request()->user();

        $roles = $user->getRoles();

        foreach ($roles as $role) {
            switch ($role->data_range) {
                case Role::ALL_DATA:
                    //全部数据
                    $isAll = true;
                    break;
                case Role::SELF_CHOOSE:
                    //自定义数据
                    $departmentIds = $role->getDepartmentId();
                    $userIds = array_merge($userIds, $this->getUserIdsByDepartmentId($departmentIds));
                    break;
                case Role::SELF_DATA:
                    //本人数据
                    $userIds[] = $user->id;
                    break;
                case Role::DEPARTMENT_DOWN_DATA: 
                    // 部门及以下数据 
                    $departmentIds = Department::getChildrenDepartmentIds($user->dept_id);
                    $userIds = $this->getUserIdsByDepartmentId($departmentIds);
                    break;
                case Role::DEPARTMENT_DATA:
                    //部门数据
                    $userIds = array_merge($userIds, $this->getUserIdsByDepartmentId([$user->dept_id]));
                    break;
                default:
                    break;
            }

            // 如果有全部数据 直接跳出
            if ($isAll) {
                break;
            }
        }

        return $userIds;
    }


    /**
     * 获取用户id
     *
     * @param array $id
     * @return array
     */
    protected function getUserIdsByDepartmentId(array $id)
    {
        return User::whereIn('dept_id', $id)->column('id');
    }


    /**
     * 判断是否有权限某操作数据
     *
     * @param int $userId 资源的拥有者ID
     * @param string $name 资源名称（如客户名称）
     * @param string|null $errorMsg 自定义错误信息模板
     * @param array $replace 替换参数
     * @return array
     */
    public function checkDataAuth(int $userId, string $name, ?string $errorMsg = null, array $replace = []) : array
    {
        $authIds = $this->getUserIdsByPermissions();

        if (!empty($authIds) && !in_array($userId, $authIds)) {
            if (!$errorMsg) {
                $errorMsg = '『{name}』操作失败，没有权限';
            }

            foreach ($replace as $key => $value) {
                $errorMsg = str_replace("{{$key}}", $value, $errorMsg);
            }

            $errorMsg = str_replace('{name}', $name, $errorMsg);
            return [false, $errorMsg];
        }

        return [true, ""];
    }

}
