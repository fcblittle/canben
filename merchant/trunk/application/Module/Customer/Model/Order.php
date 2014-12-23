<?php 

namespace Module\Customer\Model;

use System\Model;

class Order extends Model {

    private $tableOrder = '`foodcar_order`';
    private $tableOrderDish = '`foodcar_food_order`';
    private $tableOrderOffline = '`foodcar_order_offline`';

    private $tableDish = '`foodcar_official_dish_revision`';

    /**
     * 按id获取
     */
    public function getItemById($id, $uid, $fields = '*') {
        $sql = "SELECT {$fields} "
            . " FROM {$this->tableOrder} "
            . " WHERE id = ?"
            . " AND store_id = ?"
            . " AND transfered <> 0";

        return $this->db->fetch($sql, array($id, $uid));
    }

    /**
     * 获取item
     */
    public function getItem($params = array()) {
        $params = array_merge(array(
            'orderno' => '',
            'id'      => '',
            'fields'  => '*'
        ), $params);
        $conds = '';
        $binds = array();
        if ($params['id']) {
            $conds .= ' AND id = ?';
            $binds[] = $params['id'];
        }
        if ($params['orderno']) {
            $conds .= ' AND orderno = ?';
            $binds[] = $params['orderno'];
        }
        $conds .= ' AND transfered <> 0';
        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->tableOrder} "
            . " WHERE 1 {$conds}";

        return $this->db->fetch($sql, $binds);
    }

    /**
     * 获取列表
     */
    public function getItemList($params = array()) {
        $params = array_merge(array(
            'keyword'   => '',
            'limit'     => 20,
            'fields'    => '*',
            'orderno'   => null,
            'order'     => 'insert_time DESC',
            'order_date'=> array(),
            'store_id'  => null,  
            'tag'       => null,
            'user_id'   => null,
            'order_person' => '',
            'phone'     => '',
            'status'    => -1
        ), $params);
        $cond = '';
        $storeIds = is_array($params['store_id'])
            ? implode(',', $params['store_id'])
            : $params['store_id'];

        if (! empty($params['order_date'])) {
            $cond .= "AND insert_time BETWEEN ? AND ?";
            $binds[] = $params['order_date']['order_date_cur'];
            $binds[] = $params['order_date']['order_date_next'];
        }
        if ($params['tag']) {
            $cond .= " AND tag IN ({$params['tag']})";
        }
        if ($params['keyword']) {
            $cond .= ' AND title LIKE ?';
            $binds[] = "%{$params['keyword']}%";
        }
        if ($params['orderno']) {
            $cond .= ' AND orderno = ?';
            $binds[] = $params['orderno'];
        }
        if ($params['user_id'] !== null) {
            $cond .= ' AND user_id = ?';
            $binds[] = $params['user_id'];
        }
        if ($params['status'] != -1) {
            $cond .= ' AND status = ?';
            $binds[] = $params['status'];
        }
        if ($params['order_person'] !== '') {
            $cond .= ' AND order_person = ?';
            $binds[] = $params['order_person'];
        }
        if ($params['phone'] !== '') {
            $cond .= ' AND order_person_tel = ?';
            $binds[] = $params['phone'];
        }

        $sql = "SELECT {$params['fields']}"
            . " FROM {$this->tableOrder}"
            . " WHERE store_id IN({$storeIds}) {$cond}"
            . " ORDER BY {$params['order']}";

        $sqlc = "SELECT COUNT(id) AS count"
            . " FROM {$this->tableOrder}"
            . " WHERE store_id IN({$storeIds}) {$cond}";

        return array(
            'list'  => $this->db->pagerQuery($sql, $params['pager'], $binds),
            'total' => $this->db->fetch($sqlc, $binds)->count
        );
    }

    /**
     * 线下订单
     */
    public function getOrderOffline($args)
    {
        $params = array_merge(array(
            'fields'     => 'orderOffline.*, dish.sale_price AS price',
            'orderby'    => 'ORDER BY orderOffline.created DESC',
            'diner_id'   => null,
            'order_date_cur'  => null,
            'order_date_next' => null
        ), $args);

        $cond = $binds = array();
        // 餐车id
        if (! empty($params['diner_id'])) { 
            if (is_array($params['diner_id'])) {
                $ids = implode(',', $params['diner_id']);
            } else {
                $ids = $params['diner_id'];
            }

            $cond[] = "orderOffline.diner_id IN ({$ids})";
        }

        // 日期
        if (! empty($params['order_date_cur']) ) {
            $cond[] = "orderOffline.created BETWEEN ? AND ?";
            $binds[] = $params['order_date_cur'];
            $binds[] = $params['order_date_next'];
        }

        // 已付款
        $cond[] = "orderOffline.transfered <> 0";

        $cond = implode(' AND ', $cond);

        $sql = "SELECT {$params['fields']}
                FROM {$this->tableOrderOffline} AS orderOffline
                LEFT JOIN {$this->tableDish} AS dish
                ON orderOffline.dish_reversion_id = dish.id
                WHERE $cond
                {$params['orderby']}";

        return $this->db->fetchAll($sql, $binds);
    }

    /**
     * 线下订单列表
     */
    public function getOrderOfflineList($args)
    {
        $params = array_merge(array(
            'fields' => '*'
        ), $args);

        $cond = array();

        if (! empty($params['diner_id'])) {
            $ids = implode(',', $params['diner_id']);
            $cond[] = "AND diner_id IN ($ids)";
        }

        $sql = "SELECT {$params['fields']}
                FROM $this->tableOrderOffline 
                WHERE 1 $cond";
    }

    /**
     * 获取菜品
     */
    public function getDishes($orderIds, $fields = 'od.*') {
        $ids = implode(',', $orderIds);
        $list = array();
        $sql = "SELECT {$fields}"
            . " FROM {$this->tableOrder} o"
            . " RIGHT JOIN {$this->tableOrderDish} od "
                . " ON o.id = od.order_id"
            . " WHERE o.id IN ({$ids})";
        $result = $this->db->fetchAll($sql);
        if ($result === false) {
            return false;
        }
        if (empty($result)) {
            return array();
        }
        foreach ($result as $v) {
            $list[$v->order_id][$v->id] = $v;
        }
        return $list;
    }

    /**
     * 获取总数
     * @param $uid
     * @return mixed
     */
    public function getTotal($uid) {
        $sql = "SELECT COUNT(id) AS count"
            . " FROM {$this->tableOrder}"
            . " WHERE store_id = ?";
        return $this->db->fetch($sql, array($uid))->count;
    }

    /**
     * 按天统计
     * @param string $month 格式'Hm'
     * @return mixed
     */
    public function countMonthly($uid, $month) {
        $sql = "SELECT "
            . " FROM_UNIXTIME(insert_time, '%Y%m%d') AS day,"
            . " COUNT(id) AS count"
            . " FROM {$this->tableOrder}"
            . " WHERE store_id = ?"
            . " AND FROM_UNIXTIME(insert_time, '%Y%m') = ?"
            . " GROUP BY day";

        return $this->db->fetchAll($sql, array($uid, $month));
    }

    /**
     * 添加
     */
    public function add($data) {
        return $this->db->insert($this->tableOrder, $data);
    }

}