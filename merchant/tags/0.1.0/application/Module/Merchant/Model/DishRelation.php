<?php 

namespace Module\Merchant\Model;

use System\Model;

class DishRelation extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = '`foodcar_food_relation`';
    }
    
    /**
     * 按id获取
     */
    public function getItemById($id, $uid, $fields = '*') {
        $sql = "SELECT {$fields} "
            . " FROM {$this->table} "
            . " WHERE id = ?"
            . " AND merchant_id = ?";
            
        return $this->db->fetch($sql, array($id, $uid));
    }

    /**
     * 获取全部
     */
    public function getAll($params = array()) {
        $params = array_merge(array(
            'fields'   => '*',
            'order'    => 'id DESC',
            'uid'      => 0,
            'diner_id' => 0
        ), $params);
        $conds = '';
        $binds = array($params['uid']);
        if ($params['diner_id']) {
            $ids = is_array($params['diner_id'])
                ? implode(',', $params['diner_id'])
                : $params['diner_id'];
            $conds .= " AND diner_id IN ({$ids})";
        }
        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->table}"
            . " WHERE merchant_id = ? {$conds}"
            . " ORDER BY {$params['order']}";

        return $this->db->fetchAll($sql, $binds);
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
        $ids = is_array($id) ? implode(',', $id) : $id;
        $conds = array(
            'id IN (' . $ids . ') AND merchant_id = ?',
            array($uid)
        );
        return $this->db->update($this->table, $data, $conds);
    }
    
    /**
     * 删除
     */
    public function delete($ids, $uid) {
        if (is_array($ids)) {
            $ids = implode(',', $ids);
        }
        $sql = "DELETE FROM {$this->table}"
            . " WHERE id IN ({$ids})"
            . " AND merchant_id = {$uid}";
        return $this->db->execute($sql);
    }

    /**
     * 删除
     */
    public function deleteByRelation($uid, $type, $id) {
        $sql = "DELETE FROM {$this->table}"
            . " WHERE relation_type = ?"
            . " AND relation_id = ?"
            . " AND merchant_id = ?";

        return $this->db->execute($sql, array($type, $id, $uid));
    }
}