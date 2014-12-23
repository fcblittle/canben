<?php 

namespace Module\Merchant\Model;

use System\Model;

/**
 * 餐厅特点
 */
class RestaurantFeature extends Model {

    public $table = '`foodcar_storelabel`';

    /**
     * 获取列表
     */
    public function getList($params) {
        $params = array_merge(array(
            'limit'   => 20,
            'fields'  => '*',
            'order'   => 'sort ASC',
            'store_id' => 0,
            'merchant_id' => 0
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
     * 获取item
     */
    public function getItem($params = array()) {
        $params = array_merge(array(
            'id'          => 0,
            'name'        => '',
            'merchant_id' => 0,
            'fields'      => '*'
        ), $params);
        $conds = '';
        $binds = array();
        if ($params['id']) {
            $conds .= " AND id = ?";
            $binds[] = $params['id'];
        }
        if ($params['name']) {
            $conds .= " AND slable_name = ?";
            $binds[] = $params['name'];
        }
        if ($params['merchant_id']) {
            $conds .= " AND merchant_id = ?";
            $binds[] = $params['merchant_id'];
        }
        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->table} "
            . " WHERE 1 {$conds}";

        return $this->db->fetch($sql, $binds);
    }

    /**
     * 获取列表
     */
    public function getItemByText($uid, $listId, $text) {
        $sql = "SELECT * FROM {$this->table}"
            . " WHERE merchant_id = ?"
            . " AND text = ?";
        return $this->db->fetch($sql, array($uid, $listId, $text));
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
    
    /**
     * 添加
     */
    public function add($data) {
        return $this->db->insert($this->table, $data);
    }
    
    /**
     * 更新
     */
    public function update($id, $uid, $data) {
        $conds = array(
            'id = ? AND merchant_id = ?',
            array($id, $uid)
        );
        return $this->db->update($this->table, $data, $conds);
    }
    
    /**
     * 删除
     */
    public function delete($id, $uid) {
        $sql = "DELETE FROM {$this->table}"
            . " WHERE id = ?"
            . " AND merchant_id = ?";
        return $this->db->execute($sql, array($id, $uid));
    }
}