<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 版本模型
 * 
 * @author sj
 * @package version_model
 */
class Official_dish extends CI_Model {

    //构造
    public function __construct()
    {
        parent::__construct();
    }

    
    /**
     * 获取列表
     * 
     * @access public
     * @return mixed
     */
    public function get_list($params = array()) {
        $params = array_merge(array(
            'fields'  => '*',
            'status'  => null,
            'cate_id' => null,
            'name'    => '',
            'deleted' => false,
            'order'   => 'time_updated DESC,id DESC',
            'limit'   => 50,
            'page'    => 0
        ), $params);
        $list = array();
        $conds = '';
        if ($params['status'] !== null) {
            $conds .= " AND foodstatus = {$params['status']}";
        }
        if ($params['cate_id'] !== null) {
            $conds .= " AND cate_id = {$params['cate_id']}";
        }
        if ($params['name'] !== '') {
            $conds .= " AND food_name LIKE '%{$params['name']}%'";
        }
        if ($params['deleted'] === false) {
            $conds .= " AND foodstatus <> 2";
        }
        if ($params['deleted'] === true) {
            $conds .= " AND foodstatus = 2";
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_official_dish`"
            . " WHERE 1 {$conds}"
            . " ORDER BY {$params['order']}"
            . " LIMIT {$params['page']},{$params['limit']}";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum) {
            $list = $query->result_array();
        }
        $counter = "SELECT COUNT(id) AS count"
            . " FROM `foodcar_official_dish`"
            . " WHERE 1 {$conds}";
        $query = $this->db->query($counter);
        $count = $query->row()->count;

        return array(
            'list' => $list,
            'total' => $count
        );
    }

    /**
     * 获取items
     *
     * @access public
     * @return mixed
     */
    public function get_items($ids, $fields = '*') {
        $ids = is_array($ids) ? implode(',', $ids) : $ids;
        $items = array();
        $sql = "SELECT {$fields} "
            . " FROM `foodcar_official_dish`"
            . " WHERE id IN({$ids})";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as & $v) {
                $v->material && $v->material = json_decode($v->material);
                $items[$v->id] = $v;
            }
        }
        return $items;
    }

    /**
     * 获取revision items
     *
     * @access public
     * @return mixed
     */
    public function get_revision_items($ids, $fields = '*') {
        $ids = is_array($ids) ? implode(',', $ids) : $ids;
        $items = array();
        $sql = "SELECT {$fields} "
            . " FROM `foodcar_official_dish_revision`"
            . " WHERE id IN({$ids})";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as & $v) {
                $v->material && $v->material = json_decode($v->material);
                $items[$v->id] = $v;
            }
        }
        return $items;
    }

    /**
     * 添加版本
     * @param $id
     */
    public function add_revision($id) {
        $sql = "INSERT INTO `foodcar_official_dish_revision`"
            . " (source_id,food_name,supply_price,sale_price,"
            . " unit,images,description,mealtime_id,material,"
            . " cate_id,foodstatus,tag_id,time_created)"
            . " SELECT id,food_name,supply_price,sale_price,"
            . " unit,images,description,mealtime_id,material,"
            . " cate_id,foodstatus,tag_id,time_updated"
            . " FROM `foodcar_official_dish`"
            . " WHERE id = {$id}";
        $q = $this->db->query($sql);
        if ($this->db->affected_rows()) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * 根据城市id 获取菜品列表
     * 
     * @access public
     * @return mixed
     */
    public function get_foodcity($city_id) {        
        $sql = " SELECT d.id,d.food_name,d.supply_price,d.sale_price,
                        d.foodstatus,d.time_created,d.time_updated, "
              . "      cf.city_id"
              . " FROM `foodcar_official_dish` d"
              . " LEFT JOIN `foodcar_city_food` cf ON cf.food_id = d.id "
              . " WHERE cf.city_id = {$city_id}"
              . " ORDER BY d.time_updated DESC,d.id DESC";
        $query = $this->db->query($sql);
        $rownum = $query->result_array();
        return $rownum;
    }
}