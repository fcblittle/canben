<?php 

namespace Module\Merchant\Model;

use System\Model;

class Dish extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = '`foodcar_food_info`';
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
    public function getItemList($params) {
        $params = array_merge(array(
            'keyword' => '',
            'limit'   => 20,
            'fields'  => '*',
            'status'  => -1,
            'order'   => 'id DESC',
            'uid'     => 0
        ), $params);
        $binds = array($params['uid']);
        $conds = '';
        if ($params['status'] != -1) {
            $conds .= ' AND foodstatus = ?';
            $binds[] = $params['status'];
        }
        if ($params['category'] != -1) {
            $conds .= ' AND cate_id = ?';
            $binds[] = $params['category'];
        }
        if ($params['keyword']) {
            $conds .= ' AND food_name LIKE ?';
            $binds[] = "%{$params['keyword']}%";
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM {$this->table}"
            . " WHERE merchant_id = ? {$conds}"
            . " ORDER BY {$params['order']}";

        $sqlc = "SELECT COUNT(id) AS count"
            . " FROM {$this->table}"
            . " WHERE merchant_id = ? {$conds}";

        return array(
            'list'  => $this->db->pagerQuery($sql, $params['pager'], $binds),
            'total' => $this->db->fetch($sqlc, $binds)->count
        );
    }

    /**
     * 获取多个item
     *
     * @param $id
     * @param $uid
     * @param string $fields
     * @return mixed
     */
    public  function getItems($id, $uid, $fields = '*') {
        $id = implode(',', $id);
        $sql = "SELECT {$fields} FROM {$this->table}"
            . " WHERE id IN({$id})"
            . " AND merchant_id = {$uid}";

        return $this->db->fetchAll($sql);
    }

    /**
     * 获取全部
     */
    public function getAll($params = array()) {
        $params = array_merge(array(
            'fields'  => '*',
            'order'   => 'id DESC',
            'uid'     => 0
        ), $params);
        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->table}"
            . " WHERE merchant_id = ?"
            . " ORDER BY {$params['order']}";

        return $this->db->fetchAll($sql, array($params['uid']));
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