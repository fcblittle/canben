<?php 

namespace Module\Merchant\Model;

use System\Model;

class MerchantOrder extends Model {

    private $tableMerchantTrade = '`foodcar_merchant_trade`';

    private $tableMerchantOrder = '`foodcar_merchant_order`';

    private $tableMerchantOrderDetail = '`foodcar_merchant_order_detail`';

    private $tableFoodCar = '`foodcar_diner`';

    private $tableOffcialDish = '`foodcar_official_dish`';

    /**
     * 添加
     */
    public function add($merchantId, $data) {
        $this->db->beginTransaction();

        $totalPrice = 0;
        foreach ($data as $v) {
            foreach ($v as $v1) {
                $totalPrice += $v1[0] * $v1[1];
            }
        }
        // 生成交易
        $temp = array(
            'merchant_id' => $merchantId,
            'total_price' => $totalPrice,
            'status'      => 1,
            'created'     => REQUEST_TIME
        );
        $result = $this->db->insert($this->tableMerchantTrade, $temp);
        if ($result === false) {
            $this->db->rollBack();
            return false;
        }
        $tradeId = $result;

        // 生成订单
        foreach ($data as $k => $v) {
            $price = 0;
            foreach ($v as $k1 => $v1) {
                $price += $v1[0] * $v1[1];
            }
            $temp = array(
                'trade_id'   => $tradeId,
                'merchant_id' => $merchantId,
                'diner_id'    => $k,
                'total_price' => $price,
                'status'      => 0,
                'created'     => REQUEST_TIME
            );
            $orderId = $this->db->insert($this->tableMerchantOrder, $temp);
            if ($orderId === false) {
                $this->db->rollBack();
                return false;
            }
            // 添加详情
            $detail = array();
            foreach ($v as $k2 => $v2) {
                $detail[] = array(
                    'order_id'         => $orderId,
                    'dish_id'          => $k2,
                    'dish_revision_id' => $v2[2],
                    'quantity'         => $v2[1],
                    'supply_price'     => $v2[0],
                    'total_paid'       => $v2[1] * $v2[0],
                    'material'         => $v2[3]
                );
            }
            $result = $this->db->insert($this->tableMerchantOrderDetail, $detail);
            if ($result === false) {
                $this->db->rollBack();
                return false;
            }
        }

        if ($this->db->commit()) {
            return $tradeId;
        }
        return false;
    }
   
     /**
     * 获取列表
     */
    public function getOrderList($params) {
        $params = array_merge(array(
            'keyword' => '',
            'limit'   => 20,
            'fields'  => 'o.*, c.diner_name',
            'order'   => 'o.id DESC',
            'status'  => -1,
            'uid'     => 0
        ), $params);
        $binds = array();
        $binds[] = $params['uid'];
        $cond = '';
        if($params['start'])
        {
            $cond = 'AND o.created > ? ';
            $binds[] = $params['start'];
        }
        if ($params['status'] != -1) {
            $cond .= 'AND o.status = ? ';
            $binds[] = $params['status'];
        }
        if($params['end'])
        {
            $cond .= 'AND o.created < ? ';
            $binds[] = $params['end'];
        }
        if($params['order_id'])
        {
            $cond .= 'AND o.id = ? ';
            $binds[] = $params['order_id'];
        }
        
        if($params['diner_id'])
        {
            if(count($params['diner_id']) == 1)
            {
                $cond .= "AND o.diner_id = ? ";
                foreach ($params['diner_id'] as $diner) {
                    $binds[] = $diner;
                }
                   
            } 
            else{
                $cond .="AND (";
                $i = 0;
                foreach ($params['diner_id'] as $diner) {
                    
                    $i++;
                    if($i < count($params['diner_id']) ){
                        $cond .= " o.diner_id = ? OR ";
                        $binds[] = $diner;
                    } else {
                        $cond .= " o.diner_id = ? )";
                        $binds[] = $diner;
                    }
                } 

            }
            
        }

        
        $sql = "SELECT {$params['fields']}"
            . " FROM {$this->tableMerchantOrder} o"
            . " LEFT JOIN {$this->tableFoodCar} c"
            . " ON o.diner_id = c.id"
            . " WHERE o.merchant_id = ? {$cond}"
            . " ORDER BY {$params['order']}";
        
        $sqlc = "SELECT COUNT(id) AS count"
            . " FROM {$this->tableMerchantOrder} o"
            . " WHERE merchant_id = ? {$cond}";
        
        return array(
            'list'  => $this->db->pagerQuery($sql, $params['pager'], $binds),
            'total' => $this->db->fetch($sqlc, $binds)->count
        );
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
            'limit'       => 50,
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
            . " ORDER BY {$params['order']}";
        $sqlTotal =
            " SELECT COUNT(a.id) AS count"
            . " FROM `foodcar_merchant_order` a " . $join
            . " WHERE 1 {$conds}";

        return array(
            'list'  => $this->db->pagerQuery($sql, $params['pager'], $binds),
            'total' => $this->db->fetch($sqlTotal, $binds)->count
        );
    }

    /* 
     * 获取详细订单信息  
     *
     **/

    public function getOrderDetail($orderid,$userId, $fields = "od.*, d.food_name, d.images"){
        
        $ids = implode(",", $orderid);
        $sql = "SELECT {$fields}"
            . " FROM {$this->tableMerchantOrder} o"
            . " RIGHT JOIN {$this->tableMerchantOrderDetail} od"
            . " ON o.id = od.order_id"
            . " LEFT JOIN {$this->tableOffcialDish} d"
            . " ON od.dish_id = d.id"
            . " WHERE od.order_id IN ({$ids})"
            . " AND o.merchant_id = {$userId}";

        $result = $this->db->fetchAll($sql);
       
        if ($result === false) {
            return false;
        }
        if (empty($result)) {
            return array();
        }
        foreach ($result as $v) {
            $order[$v->order_id][$v->id] = $v;
        }

        return $order;
    }

    /*
    * 获取订单号和商户id
    */
    public function getItems($params = array()) {
        $params = array_merge(array(
            'ids'         => '',
            'merchant_id' => 0,
            'fields'      => '*'
        ), $params);
        $ids = implode(',', $params['ids']);
        $binds = array($params['merchant_id']);

        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->tableMerchantOrder} "
            . " WHERE merchant_id = ?"
            . " AND id IN({$ids})";

        return $this->db->fetchAll($sql, $binds);
    }

    /**
     * 获取订单中的餐车
     * @param $params
     */
    public function getDiners($params) {
        $params = array_merge(array(
            ''
        ), $params);
    }

    /*
    * 获取订单号和商户id
    */
    public function getItem($params = array()) {
        $params = array_merge(array(
            'id'      => '',
            'merchant_id' => 0,
            'fields'  => '*'
        ), $params);
        $conds = '';
        $binds = array($params['merchant_id']);
        if ($params['id']) {
            $conds .= ' AND id = ?';
            $binds[] = $params['id'];
        }

        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->tableMerchantOrder} "
            . " WHERE merchant_id = ? {$conds}";

        return $this->db->fetch($sql, $binds);
    }

     /**
     * 更新
     */
    public function update($orderno, $data) {
        $conds = array('id = ?', array($orderno));
        return $this->db->update($this->tableMerchantOrder, $data, $conds);
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
        $result = $this->db->fetchAll($sql);
        if (! $result) {
            return array();
        }
        foreach ($result as $v) {
            $list[$v->id] = $v;
        }
        return $list;
    }

    /**
     * 获取菜品版本
     */
    public function getDisheRevisions($orderIds, $fields = '*') {
        $ids = implode(',', $orderIds);
        $list = array();
        $sql = "SELECT {$fields}"
            . " FROM `foodcar_merchant_order_detail` "
            . " WHERE order_id IN ({$ids})";
        $result = $this->db->fetchAll($sql);
        if (! $result) {
            return array();
        }
        foreach ($result as $v) {
            $list[$v->order_id] = $v;
        }
        return $list;
    }

}