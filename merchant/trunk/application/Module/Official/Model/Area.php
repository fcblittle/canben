<?php

namespace Module\Official\Model;

use System\Model;

class Area extends Model {

    private $table = '`foodcar_diner_area`';

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