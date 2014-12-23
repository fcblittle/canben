<?php 

namespace Module\Merchant\Model;

use System\Model;

/**
 * 餐厅菜系
 */
class Cuisine extends Model {

    public $table = '`foodcar_cuisine`';

    /**
     * 获取列表
     */
    public function getList($params) {
        $params = array_merge(array(
            'limit'   => 20,
            'fields'  => '*',
            'order'   => 'sort ASC',
        ), $params);
        $binds = array();
        $conds = '';
        if ($params['store_id']) {
            $conds .= " AND store_id = ?";
            $binds[] = $params['store_id'];
        }
        if ($params['merchant_id']) {
            $conds .= " AND merchant_id = ?";
            $binds[] = $params['merchant_id'];
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM {$this->table}"
            . " WHERE 1 {$conds}"
            . " ORDER BY {$params['order']}";

        $sqlc = "SELECT COUNT(id) AS count"
            . " FROM {$this->table}"
            . " WHERE 1 {$conds}";

        return array(
            'list'  => $this->db->pagerQuery($sql, $params['pager'], $binds),
            'total' => $this->db->fetch($sqlc, $binds)->count
        );
    }

    /**
     * 获取全部
     */
    public function getAll($params = array()) {
        $params = array_merge(array(
            'fields'  => '*',
            'order'   => 'sort ASC',
        ), $params);
        $items = array();
        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->table}"
            . " WHERE 1"
            . " ORDER BY {$params['order']}";
        $result = $this->db->fetchAll($sql);
        if ($result) {
            foreach ($result as & $v) {
                $items[$v->id] = $v;
            }
        }

        return $items;
    }
    
}