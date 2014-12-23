<?php 

namespace Module\Merchant\Model;

use System\Model;

/**
 * 菜品分类模型
 */
class DishCategory extends Model {

    public $table = '`foodcar_dish_category`';

    /**
     * 按id获取
     */
    public function getItemById($id, $merchantId, $fields = '*') {
        $sql = "SELECT {$fields} "
            . " FROM {$this->table} "
            . " WHERE id = ?"
            . " AND merchant_id = ?";

        return $this->db->fetch($sql, array($id, $merchantId));
    }

    /**
     * 获取列表
     */
    public function getItemByName($merchantId, $name) {
        $sql = "SELECT * FROM {$this->table}"
            . " WHERE merchant_id = ?"
            . " AND name = ?";
        return $this->db->fetch($sql, array($merchantId, $name));
    }
    
    /**
     * 获取全部
     */
    public function getAll($params = array()) {
        $params = array_merge(array(
            'fields'  => '*',
            'order'   => 'weight ASC',
            'merchant_id' => 0
        ), $params);
        $items = array();
        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->table}"
            . " WHERE merchant_id = ?"
            . " ORDER BY {$params['order']}";
        $binds = array(
            $params['merchant_id']
        );
        $result = $this->db->fetchAll($sql, $binds);
        if ($result) {
            foreach ($result as & $v) {
                $items[$v->id] = $v;
            }
        }

        return $items;
    }

    /**
     * 获取items
     */
    public function getItems($params = array()) {
        $params = array_merge(array(
            'fields'  => '*',
            'ids'     => array(),
            'merchant_id' => 0
        ), $params);
        $items = array();
        $ids = implode(',', $params['ids']);
        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->table}"
            . " WHERE merchant_id = ?"
            . " AND id IN ({$ids})";
        $binds = array(
            $params['merchant_id']
        );
        $result = $this->db->fetchAll($sql, $binds);
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