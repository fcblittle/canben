<?php

namespace Module\Official\Model;

use System\Model;

class Dish extends Model {

    private $table = '`foodcar_official_dish`';
    private $tableRevision = '`foodcar_official_dish_revision`';

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
        if ($params['email']) {
            $conds .= ' AND email = ?';
            $binds[] = $params['email'];
        }
        $sql = "SELECT {$$params['fields']} "
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
            'ids'      => array(),
            'fields'   => '*'
        ), $params);
        $items = array();
        $conds = '';
        if ($params['ids']) {
            $ids = implode(',', $params['ids']);
            $conds .= ' AND id IN(' . $ids . ')';
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

    /**
     * 获取revision items
     *
     * @param array $params 参数
     * @return 查询结果
     */
    public function getRevisionItems($params =array()) {
        $params = array_merge(array(
            'ids'      => array(),
            'fields'   => '*'
        ), $params);
        $items = array();
        $conds = '';
        if ($params['ids']) {
            $ids = implode(',', $params['ids']);
            $conds .= ' AND id IN(' . $ids . ')';
        }
        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->tableRevision} "
            . " WHERE 1 {$conds} ";
        $result = $this->db->fetchAll($sql);
        if ($result) {
            foreach ($result as & $v) {
                $items[$v->id] = $v;
            }
        }
        return $items;
    }

    /**
     * 获取所有菜品
     * @param array $params
     * @return array
     */
    public function getAll($params =array()) {
        $params = array_merge(array(
            'fields'   => '*'
        ), $params);
        $items = array();
        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->table} ";
        $result = $this->db->fetchAll($sql);
        if ($result) {
            foreach ($result as & $v) {
                $items[$v->id] = $v;
            }
        }
        return $items;
    }
}