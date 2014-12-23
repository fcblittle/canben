<?php

namespace Application\Component\Account;

use System\Bootstrap;

class Permission {
    
    private $permissions = array();
    
    private $permissionNames = array();
    
    private $isSuperUser = false;
    
    public function __construct($user) {
        if ($user->uid == 1) {
            $this->isSuperUser = true;
            return;
        }
        $model = Bootstrap::model('Application:Role');
        $this->permissions = $model->getPermissionDetails(array_keys($user->roles));
        if ($this->permissions) {
            foreach ($this->permissions as $v) {
                 $this->permissionNames[$v->id] = $v->name;
            }
        }
    }
    
    /**
     * 检查权限
     * 
     * @param string $permissions 逗号分隔的权限列表
     * @return bool 检查结果
     */
    public function check($permissions) {
        if (! $permissions || $this->isSuperUser) {
            return true;
        }
        if (! $this->permissionNames) {
            return false;
        }
        $perms = explode(',', $permissions);
        foreach ($perms as $v) {
            if (in_array($v, $this->permissionNames)) {
                return true;
            }
        }
        return false;
    }
}
