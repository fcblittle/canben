<?php 

namespace Module\Merchant\Model;

use System\Model;

class Diningcar extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = '`foodcar_diner`';
        $this->area = '`foodcar_diner_area`';
    }

    /**
     * 获取city_id
     */
    public function getCityId($args) {
        
        $binds = array();
        
        $sql = "SELECT {$args['fields']} "
            . " FROM {$this->area} "
            . " WHERE id = ?";
        $binds[] = $args['id'];
        
        return $this->db->fetch($sql, $binds);
    }

    /**
     * 按id获取
     */
    public function getItemById($id, $fields = '*') {
        $binds = array();
        
        $sql = "SELECT {$fields} "
            . " FROM {$this->table} "
            . " WHERE id = ?";
        $binds[] = $id;
        
        return $this->db->fetch($sql, $binds);
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
            'uid'     => null,
            'status'  => null,
            'role'    => null
        ), $params);
        $binds = array();
        $conds = '';

        if ($params['uid'] !== null) {
            $conds .= " AND merchant_id = ?";
            $binds[] = $params['uid'];
        }
        if ($params['status'] !== null) {
            $conds .= " AND store_stauts = ?";
            $binds[] = $params['status'];
        }
        if ($params['role'] !== null) {
            $conds .= " AND role = ?";
            $binds[] = $params['role'];
        }

        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->table}"
            . " WHERE 1 {$conds}"
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
     * 获取餐车管理者
     * 
     * @param int/array $diner_id 餐车id
     * @param string $fields
     * 
     * @return array $result 餐车管理者
     */
    public function getDinerManager($diner_id, $fields = '*')
    {
        $ids = array();
        if (is_array($diner_id)) {
            $ids = implode(',', $diner_id);
        } else {
            $ids = $diner_id;
        }

        $sql = "SELECT {$fields}"
            ." FROM `biz_staff`"
            ." WHERE diner_id IN ($ids)";

        return $this->db->fetchAll($sql, array());
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