<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 版本模型
 * 
 * @author sj
 * @package version_model
 */
class Merchant_order extends CI_Model {

    //构造
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 查询列表
     * @param array $params
     * @return array
     */
    public function getList($params = array()) {
        $params = array_merge(array(
            'start'       => null,
            'end'         => null,
            'paid_start'  => null,
            'paid_end'    => null,
            'confirmed_start' => null,
            'confirmed_end'   => null,
            'fields'      => 'a.*',
            'trade_id'    => null,
            'merchant_id' => null,
            'diner_id'    => null,
            'status'      => null,
            'area'        => 0,
            'limit'       => 20,
            'sum'         => true,
            'order'       => 'a.id DESC',
            'offset'      => 0
        ), $params);
        $result = array(
            'total' => null,
            'list'  => array()
        );
        $conds = $join = '';
        $binds = array();
        if ($params['start'] !== null) {
            $conds .= " AND a.created >= ?";
            $binds[] = $params['start'];
        }
        if ($params['end'] !== null) {
            $conds .= " AND a.created <= ?";
            $binds[] = $params['end'];
        }
        if ($params['paid_start'] !== null) {
            $conds .= " AND a.time_paid >= ?";
            $binds[] = $params['paid_start'];
        }
        if ($params['paid_end'] !== null) {
            $conds .= " AND a.time_paid <= ?";
            $binds[] = $params['paid_end'];
        }
        if ($params['confirmed_start'] !== null) {
            $conds .= " AND a.time_confirmed >= ?";
            $binds[] = $params['confirmed_start'];
        }
        if ($params['confirmed_end'] !== null) {
            $conds .= " AND a.time_confirmed <= ?";
            $binds[] = $params['confirmed_end'];
        }
        if ($params['trade_id'] !== null) {
            $conds .= " AND a.trade_id = ?";
            $binds[] = $params['trade_id'];
        }
        if ($params['merchant_id'] !== null) {
            $conds .= " AND a.merchant_id = ?";
            $binds[] = $params['merchant_id'];
        }
        if ($params['diner_id'] !== null) {
            $conds .= " AND a.diner_id = ?";
            $binds[] = $params['diner_id'];
        }
        if ($params['status'] !== null) {
            if (is_array($params['status'])) {
                $statuses = implode(',', $params['status']);
                $conds .= " AND a.status IN({$statuses})";
            } else {
                $conds .= " AND a.status = ?";
                $binds[] = $params['status'];
            }
        }
        if ($params['area'] != 0) {
            $join .= "LEFT JOIN `foodcar_diner` b ON a.diner_id = b.id";
            $conds .= " AND b.area = ?";
            $binds[] = $params['area'];
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_merchant_order` a " . $join
            . " WHERE 1 {$conds}"
            . " ORDER BY {$params['order']}"
            . " LIMIT {$params['offset']},{$params['limit']}";
        $query = $this->db->query($sql, $binds);
        if ($query->num_rows() > 0) {
            $result['list'] = $query->result();
        }
        if ($params['sum']) {
            $sqlTotal =
                  " SELECT COUNT(a.id) AS count"
                . " FROM `foodcar_merchant_order` a " . $join
                . " WHERE 1 {$conds}";
            $query = $this->db->query($sqlTotal, $binds);
            $result['total'] = $query->row()->count;
        }

        return $result;
    }

    /**
     * 统计菜品数
     * @param array $params
     * @return array
     */
    public function sumDish($params = array()) {
        $params = array_merge(array(
            'order_id' => null   
        ), $params);
        if (is_array($params['order_id'])) {
            $params['order_id'] = implode(',', $params['order_id']);
        }
        $sql = "SELECT dish_id,SUM(quantity) AS sum"
            . " FROM `foodcar_merchant_order_detail`"
            . " WHERE order_id IN ({$params['order_id']})"
            . " GROUP BY dish_id";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return array();
    }

    /**
     * 获取菜品
     */
    public function getDishes($orderIds, $fields = '*') {
        $ids = implode(',', $orderIds);
        $list = array();
        $sql = "SELECT {$fields}"
            . " FROM `foodcar_merchant_order_detail` "
            . " WHERE order_id IN ({$ids})";
        $q = $this->db->query($sql);
        if (! $q->num_rows) {
            return array();
        }
        $result = $q->result();
        foreach ($result as & $v) {
            if ($v->material) {
                $v->material = json_decode($v->material, true);
            }
            $list[$v->id] = $v;
        }
        return $list;
    }

    /**
     * 添加采购信息
     * @author liuyunxia
     */
    public function add_orderlist($time_send, $diner_id, $merchant_id, 
                                  $food_id, $dish_revision_id, $number) {
        //总额初始值
        $total = 0;
        //处理数据 
        foreach ($food_id as $key => $value) {
            $sql = "select supply_price,material from  foodcar_official_dish_revision 
                    where id=".$dish_revision_id[$key];
            $query = $this->db->query($sql);
            $row = $query->row();
            $supply_price[$key] = $row->supply_price;
            $material[$key] = $row->material;
            $total += $number[$key] * $row->supply_price;
        } 
        //整合数据
        $date['merchant_id'] = $merchant_id;
        $date['diner_id'] = $diner_id;
        $date['total_price'] = $total;
        $date['created'] = time();
        $date['time_send'] = $time_send;
        $result = $this->db->insert('foodcar_merchant_order',$date);
        $order_id = $this->db->insert_id();
        foreach ($food_id as $key => $value) {
            $item['order_id'] = $order_id;
            $item['dish_id'] = $value;
            $item['dish_revision_id'] = $dish_revision_id[$key];
            $item['quantity'] = $number[$key];
            $item['supply_price'] = $supply_price[$key];
            $item['total_paid'] = $supply_price[$key] * $number[$key];
            $item['material'] = $material[$key];
            $re = $this->db->insert('foodcar_merchant_order_detail',$item);
        }
        return $re;
    }

}