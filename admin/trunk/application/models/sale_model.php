<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 *
 */
class Sale_model extends CI_Model {
    //构造
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 得到商户列表 #####
     *
     * 
     */
    public function get_online_list($params = array()){
    	$params = array_merge(array(
            'fields'        => 'o.*, a.merchant_name, b.realname',
            'order'         => 'o.insert_time',
            'start'         =>'',
            'end'           =>'',
            'diner_id'      =>'',
            'manager_name'  => '',
            'diner_name'    => '',
            'merchant_name' => '',
            ),$params);
        $list = array();
        $conds = '';
        if($params['start'])
        {
            $conds .= " AND o.insert_time > {$params['start']}";
        }
        if($params['end'])
        {
            $conds .= " AND o.insert_time < {$params['end']}";
        }
        if($params['diner_id'])
        {
            $conds .=" AND o.store_id = {$params['diner_id']}";
        }
        // 经营者名称
        if ($params['manager_name']) {
            $conds .= " AND b.realname LIKE '%{$params['manager_name']}%'";
        }
        // 餐车名称
        if ($params['diner_name']) {
            $conds .= " AND o.store_name LIKE '%{$params['diner_name']}%'";
        }
        // 商户名称
        if ($params['merchant_name']) {
            $conds .= " AND a.merchant_name LIKE '%{$params['merchant_name']}%'";
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_order` AS o"
            . " LEFT JOIN `foodcar_diner` AS a ON o.store_id = a.id"
            . " LEFT JOIN `biz_staff` AS b ON o.store_id = b.diner_id"
            . " WHERE o.tag = 2 and b.role = 1 {$conds}"
            . " ORDER BY {$params['order']}";

        $query = $this->db->query($sql);

        $rownum = $query->num_rows();
        if($rownum) {
            $list = $query->result();
        }
        return $list;
        
    }

    public function get_offline_list($params = array()){
        $params = array_merge(array(
            'fields'        => 'o.*, a.merchant_name,a.diner_name, b.realname,c.sale_price',
            'order'         => 'o.created',
            'start'         =>'',
            'end'           =>'',
            'diner_id'      =>'',
            'manager_name'  => '',
            'diner_name'    => '',
            'merchant_name' => '',
            ),$params);
        $list = array();
        $conds = '';
        if($params['start'])
        {
            $conds .= " AND o.created > {$params['start']}";
        }
        if($params['end'])
        {
            $conds .= " AND o.created < {$params['end']}";
        }
        if($params['diner_id'])
        {
            $conds .=" AND o.diner_id = {$params['diner_id']}";
        }
        // 经营者名称
        if ($params['manager_name']) {
            $conds .= " AND b.realname LIKE '%{$params['manager_name']}%'";
        }
        // 餐车名称
        if ($params['diner_name']) {
            $conds .= " AND a.diner_name LIKE '%{$params['diner_name']}%'";
        }
        // 商户名称
        if ($params['merchant_name']) {
            $conds .= " AND a.merchant_name LIKE '%{$params['merchant_name']}%'";
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_order_offline` AS o"
            . " LEFT JOIN `foodcar_diner` AS a ON o.diner_id = a.id"
            . " LEFT JOIN `biz_staff` AS b ON o.diner_id = b.diner_id"
            . " LEFT JOIN `foodcar_official_dish_revision` AS c ON o.dish_reversion_id = c.id"
            . " WHERE b.role = 1 {$conds}"
            . " ORDER BY {$params['order']}";

        $query = $this->db->query($sql);

        $rownum = $query->num_rows();
        if($rownum) {
            $list = $query->result();
        }
        return $list;
    }

    public function get_online_detail($params = array()){
        $params = array_merge(array(
            'fields'        => '*',
            'order'         => 'id',
            'limit'         => 20,
            'page'          => 0,
        ), $params);
        // var_dump($params);
        $list = array();
        $conds = '';
        //
        if($params['start'])
        {
            $conds .= " AND insert_time > {$params['start']}";
        }
        if($params['end'])
        {
            $conds .= " AND insert_time < {$params['end']}";
        }
        if($params['store_id'])
        {
            $conds .= " AND store_id = {$params['store_id']}";
        }
        if($params['order_type'] != -1)
        {
            $conds .= " AND order_type = {$params['order_type']}";
        }
        if($params['delivery_methods'] != -1)
        {
            $conds .= " AND delivery_methods = {$params['delivery_methods']}";
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_order`"
            . " WHERE tag = 2 {$conds}"
            . " ORDER BY {$params['order']}"
            . " LIMIT {$params['page']},{$params['limit']}";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum) {
            $list = $query->result();
        }
        $counter = "SELECT COUNT(id) AS count"
            . " FROM `foodcar_order`"
            . " WHERE tag = 2 {$conds}";
        $query = $this->db->query($counter);
        $count = $query->row()->count;

        return array(
            'list' => $list,
            'total' => $count
        );
    }

    public function get_offline_detail($params = array()){
        $params = array_merge(array(
            'fields'        => 'a.* ,b.sale_price,b.food_name',
            'order'         => 'a.id',
            'start'         =>'',
            'end'           =>'',
            'diner_id'      =>'',
            ),$params);
        $list = array();
        $conds = '';
        if($params['start'])
        {
            $conds .= " AND a.created > {$params['start']}";
        }
        if($params['end'])
        {
            $conds .= " AND a.created < {$params['end']}";
        }
        if($params['diner_id'])
        {
            $conds .=" AND a.diner_id = {$params['diner_id']}";
        }
        
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_order_offline` AS a"
            . " INNER JOIN `foodcar_official_dish_revision` AS b ON a.dish_reversion_id = b.id"
            . " WHERE 1 {$conds}"
            . " ORDER BY {$params['order']}";
        

        $query = $this->db->query($sql);

        $rownum = $query->num_rows();
        if($rownum) {
            $list = $query->result();
        }
        return $list;
    }

}