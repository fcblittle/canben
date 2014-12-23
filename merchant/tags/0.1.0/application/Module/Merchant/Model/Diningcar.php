<?php 

namespace Module\Merchant\Model;

use System\Model;

class Diningcar extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = '`foodcar_diner`';
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
     * 获取餐车列表
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
            $cond = ' AND diner_name LIKE ?';
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
     * 获取全部
     */
    public function getAll($params = array()) {
        $params = array_merge(array(
            'fields'  => '*',
            'order'   => 'id DESC',
            'uid'     => 0,
            'status'  => null
        ), $params);
        $binds = array(
            $params['uid'],
        );
        $conds = '';
        if ($params['status'] !== null) {
            $conds .= " AND store_stauts = ?";
            $binds[] = $params['status'];
        }
        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->table}"
            . " WHERE merchant_id = ?"
            . " {$conds}"
            . " ORDER BY {$params['order']}";

        return $this->db->fetchAll($sql, $binds);
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
     * 获取我的员工数
     * @param $uid
     * @return mixed
     */
    public function getTotal($uid) {
        $sql = "SELECT COUNT(id) AS count"
            . " FROM {$this->table}"
            . " WHERE merchant_id = ?";
        return $this->db->fetch($sql, array($uid))->count;
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