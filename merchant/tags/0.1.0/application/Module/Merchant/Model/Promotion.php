<?php 

namespace Module\Merchant\Model;

use System\Model;

class Promotion extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = '{promotion}';
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
     * 获取列表
     */
    public function getItemList($params = array()) {
        $params = array_merge(array(
            'keyword' => '',
            'limit'   => 20,
            'fields'  => '*',
            'order'   => 'id DESC',
            'uid'     => 0
        ), $params);
        $binds = array($params['uid']);
        $cond = '';
        if ($params['keyword']) {
            $cond = ' AND title LIKE ?';
            $binds[] = "%{$params['keyword']}%";
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM {$this->table}"
            . " WHERE merchant_id = ? {$cond}"
            . " ORDER BY {$params['order']}";

        $sqlc = "SELECT COUNT(id) AS count"
            . " FROM {$this->table}"
            . " WHERE merchant_id = ? {$cond}";

        return array(
            'list'  => $this->db->pagerQuery($sql, $params['pager'], $binds),
            'total' => $this->db->fetch($sqlc, $binds)->count
        );
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
    public function update($id, $data) {
        return $this->db->update($this->table, $data, array('id = ?', array($id)));
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