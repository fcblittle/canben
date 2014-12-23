<?php

namespace Module\Account\Model;

use System\Model;

class User extends Model {

    public function getItem(array $params) {
        $params['fields'] = $params['fields'] ?: '*';
        $conds = '';
        $binds = array();
        if ($params['id']) {
            $conds .= ' AND id = ?';
            $binds[] = $params['id'];
        }
        $sql = "SELECT {$params['fields']} "
            . " FROM `foodcar_merchant`"
            . " WHERE 1 {$conds}"
            . " LIMIT 1";
        $result = $this->db->fetch($sql, $binds);
        return $result;
    }

    /**
     * 更新
     */
    public function update($uid, $data) {
        $conds = array('id = ?', array($uid));
        return $this->db->update('`foodcar_merchant`', $data, $conds);
    }

}