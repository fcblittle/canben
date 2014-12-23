<?php

namespace Application\Model;

use System\Model;

class Role extends Model {

    /**
     * 获取角色权限
     */
    public function getPermissionDetails(array $roleIds) {
        $roleIds = implode(',', $roleIds);
        $list = array();
        $sql = "SELECT * FROM {role_permission} rp"
            . " LEFT JOIN {permission} p "
            . "     ON rp.permissionId = p.id"
            . " WHERE rp.roleId IN({$roleIds})";
        return $this->db->fetchAll($sql);
    }
}