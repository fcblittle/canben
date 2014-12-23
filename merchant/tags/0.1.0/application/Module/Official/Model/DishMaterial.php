<?php

namespace Module\Official\Model;

use System\Model;

class DishMaterial extends Model {

    private $table = '`foodcar_official_dish_material`';
    private $tableRevision = '`foodcar_official_dish_material_revision`';

    /**
     * 获取items
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
}