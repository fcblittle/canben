<?php 

namespace Module\Customer\Model;

use System\Model;

/**
 * 退款模型
 * Class Refund
 * @package Module\Merchant\Model
 */
class Refund extends Model {

    private $table = '`foodcar_apply_refund`';

    /**
     * 获取列表
     */
    public function getItemList($params = array()) {
        $params = array_merge(array(
            'order_no' => '',
            'limit'    => 20,
            'fields'   => 'a.*',
            'status'   => -1,
            'order'    => 'a.id DESC',
            'user_id'  => -1,
            'store_id' => array()
        ), $params);
        $binds = array();
        $cond = '';
        $storeIds = is_array($params['store_id'])
            ? implode(',', $params['store_id'])
            : $params['store_id'];
        if ($params['orderno']) {
            $cond .= ' AND a.orderno = ?';
            $binds[] = $params['orderno'];
        }
        if ($params['status'] !== -1) {
            $cond .= ' AND a.status = ?';
            $binds[] = $params['status'];
        }
        if ($params['order_no'] !== '') {
            $cond .= ' AND a.order_no = ?';
            $binds[] = $params['order_no'];
        }
        if ($params['user_id'] == -2) {
            return array('list' => array(), 'total' => 0);
        }
        if ($params['user_id'] != -1) {
            $cond .= ' AND b.user_id = ?';
            $binds[] = $params['user_id'];
        }

        $sql = "SELECT {$params['fields']}"
            . " FROM {$this->table} a"
            . " LEFT JOIN `foodcar_order` b"
                . " ON a.order_no = b.orderno"
            . " WHERE b.store_id IN({$storeIds}) {$cond}"
            . " ORDER BY {$params['order']}";

        $sqlc = "SELECT COUNT(a.id) AS count"
            . " FROM {$this->table} a"
            . " LEFT JOIN `foodcar_order` b"
                . " ON a.order_no = b.orderno"
            . " WHERE b.store_id IN({$storeIds}) {$cond}";

        return array(
            'list'  => $this->db->pagerQuery($sql, $params['pager'], $binds),
            'total' => $this->db->fetch($sqlc, $binds)->count
        );
    }

    /**
     * 更新
     */
    public function update($orderno, $data) {
        $conds = array('order_no = ?', array($orderno));
        return $this->db->update($this->table, $data, $conds);
    }

    /**
     * 更新已完成的订单退款状态
     * @return mixed
     */
    public function updateCompletedOrders() {
        $sql = "UPDATE {$this->table}"
            . " SET status = 4"
            . " WHERE status = 0"
            . " AND order_no IN("
            . " SELECT orderno FROM `foodcar_order`"
            . " WHERE status = 1)";
        return $this->db->execute($sql);
    }
}