<?php 

namespace Module\Merchant\Model;

use System\Model;

class Staff extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = '`biz_staff`';
    }

    /**
     * 获取管理员
     */
    public function getItem($id, $fields = '*')
    {
        $binds = array();

        $sql = "SELECT {$fields} "
            . " FROM {$this->table} "
            . " WHERE id = ?";
        $binds[] = $id;
        
        return $this->db->fetch($sql, $binds);
    }

    /**
     * 按id获取
     */
    public function getItemById($id, $uid = 0, $fields = '*') {
        $binds = array();

        $sql = "SELECT {$fields} "
            . " FROM {$this->table} "
            . " WHERE diner_id = ?";
        $binds[] = $id;

        if ($uid) {
            $sql .= " AND merchant_id = ?";
            $binds[] = $uid;
        }

        return $this->db->fetch($sql, $binds);
    }

    /**
     * 获取餐车经营者
     * @param 
     */
    public function getDinerManager($dinerId, $fields = '*')
    {
        $sql = "SELECT {$fields}"
                ." FROM {$this->table}"
                ." WHERE diner_id = ? && status <> -1"
                ." AND role=1";

        return $this->db->fetch($sql, array($dinerId));
    }

    /**
     * 按username获取
     */
    public function getItemByName($username, $fields = '*') {
        $sql = "SELECT {$fields} "
            . " FROM {$this->table} "
            . " WHERE username = ?"
            . " LIMIT 1";

        return $this->db->fetch($sql, array($username));
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
            . " WHERE merchant_id = ? {$cond} && status <> -1"
            . " ORDER BY {$params['order']}";

        $sqlc = "SELECT COUNT(id) AS count"
            . " FROM {$this->table}"
            . " WHERE merchant_id = ? {$cond} && status <> -1";

        return array(
            'list'  => $this->db->pagerQuery($sql, $params['pager'], $binds),
            'total' => $this->db->fetch($sqlc, $binds)->count
        );
    }
    /**
     * 通过餐车ID获取列表
     */
    public function getItemListByDinerId($params = array()) {
        $params = array_merge(array(
            'keyword' => '',
            'limit'   => 20,
            'fields'  => '*',
            'order'   => 'status DESC, id DESC',
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
            . " WHERE diner_id = ? {$cond}"
            . " ORDER BY {$params['order']}";

        $sqlc = "SELECT COUNT(id) AS count"
            . " FROM {$this->table}"
            . " WHERE diner_id = ? {$cond}";

        return array(
            'list'  => $this->db->pagerQuery($sql, $params['pager'], $binds),
            'total' => $this->db->fetch($sqlc, $binds)->count
        );
    }

    /**
     * 获取我的员工数
     * @param $uid
     * @return mixed
     */
    public function getTotal($uid) {
        $sql = "SELECT COUNT(id) AS count"
            . " FROM {$this->table}"
            . " WHERE merchant_id = ? && status <> -1";
        return $this->db->fetch($sql, array($uid))->count;
    }
     /**
     * 通过餐车号获取我的员工数
     * @param $uid
     * @return mixed
     */
    public function getTotalByDinerId($uid) {
        $sql = "SELECT COUNT(id) AS count"
            . " FROM {$this->table}"
            . " WHERE diner_id = ? && status <> -1";
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
        $conds = array('id = ? AND merchant_id = ?', array($id, $uid));
        return $this->db->update($this->table, $data, $conds);
    }
    
    /**
     * 删除
     */
    public function del($id)
    {
        return $this->db->update($this->table, array('status' => -1), array('id='.$id));
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
    /**
     * manager删除店小二
     */
    public function deleteById($id) {
        $sql = "DELETE FROM {$this->table}"
            . " WHERE id = ?"
            . " AND role = 2";

        return $this->db->execute($sql, array($id));
    }


}