<?php

namespace Module\Customer\Model;

use System\Model;

class User extends Model {

    private $table = '`foodcar_userinfo`';
    
    /**
     * 获取用户
     * 
     * @param array $params 参数
     * @return 查询结果
     */
    public function getItem($params =array()) {
        $params = array_merge(array(
            'id'       => 0,
            'nickname' => '',
            'mobile_phone' => '',
            'email'    => '',
            'fields'   => '*'
        ), $params);
        $conds = '';
        $binds = array();
        if ($params['id']) {
            $conds .= ' AND id = ?';
            $binds[] = $params['id'];
        }
        if ($params['nickname']) {
            $conds .= ' AND nickname = ?';
            $binds[] = $params['nickname'];
        }
        if ($params['mobile_phone']) {
            $conds .= ' AND mobile_phone = ?';
            $binds[] = $params['mobile_phone'];
        }
        if ($params['email']) {
            $conds .= ' AND email = ?';
            $binds[] = $params['email'];
        }
        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->table} "
            . " WHERE 1 {$conds} "
            . " LIMIT 1";

        return $this->db->fetch($sql, $binds);
    }

    /**
     * 获取用户
     *
     * @param array $params 参数
     * @return 查询结果
     */
    public function getItems($params =array()) {
        $params = array_merge(array(
            'id'       => array(),
            'fields'   => '*'
        ), $params);
        $items = array();
        $conds = '';
        if ($params['id']) {
            $id = implode(',', $params['id']);
            $conds .= ' AND id IN(' . $id . ')';
        }
        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->table} "
            . " WHERE 1 {$conds} ";
        $result = $this->db->fetchAll($sql);
        if ($result) {
            foreach ($result as & $v) {
                $items[$v->id] = $v;
            }
        }
        return $items;
    }
}