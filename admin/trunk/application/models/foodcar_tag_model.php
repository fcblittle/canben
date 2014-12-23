<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 标签模型
 *
 * @author 再晨
 * @package Foodcar_tag_model
 */
class Foodcar_tag_model extends CI_Model {
    //构造
    public function __construct()    {
        parent::__construct();
    }
    //取出官方所有标签或是分类
    public function getAllList($table){
        $this->db->order_by('sort','asc');
        $query = $this->db->get($table);
        $res = $query->result_array();
        return $res;
    }
    //
    public function getinfo_by_classid($table,$cid){
        $query = $this->db->get_where($table, array('id' => $cid));
        $res = $query->row_array();
        return $res;
    }
    //
    public function getfoodlabellist(){
        $sql = "SELECT * FROM foodcar_foodlabel ORDER BY sort asc ";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        $res_num = count($res);
        for($i=0;$i<$res_num;$i++){
            if($res[$i]['tag'] == "diner"){
                $this->db->select('diner_name');
                $this->db->where('id', $res[$i]['storeid']); 
                $query = $this->db->get('foodcar_diner');
                $res_1 = $query->row_array();
                $res[$i]["store_name"] = $res_1["diner_name"];
            }elseif ($res[$i]['tag'] == "store") {
                $this->db->select('store_name');
                $this->db->where('id', $res[$i]['storeid']); 
                $query = $this->db->get('foodcar_store');
                $res_2 = $query->row_array();
                $res[$i]["store_name"] = $res_2["store_name"];
            }else{
                $res[$i]["store_name"] = "官方";
            }
        }
        return $res;
    }

    /**
     * 获取多个item
     * @param array $params
     * @return array
     */
    public function get_items($params = array()) {
        $params = array_merge(array(
            'fields' => '*',
            'ids'    => array(),
        ), $params);
        $conds = '';
        $items = array();
        if ($params['ids']) {
            $ids = implode(',', $params['ids']);
            $conds .= " AND id IN({$ids})";
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_foodclass`"
            . " WHERE 1 {$conds}";
        $query = $this->db->query($sql);
        $result = $query->result();
        if ($result) {
            foreach ($result as & $v) {
                $items[$v->id] = $v;
            }
        }
        return $items;
    }

    /**
     * 获取全部
     * @param array $params
     * @return array
     */
    public function get_all($params = array()) {
        $params = array_merge(array(
            'fields' => '*',
            'order'  => 'sort ASC,id DESC'
        ), $params);
        $items = array();
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_foodclass`"
            . " ORDER BY {$params['order']}";
        $query = $this->db->query($sql);
        $result = $query->result();
        if ($result) {
            foreach ($result as & $v) {
                $items[$v->id] = $v;
            }
        }
        return $items;
    }
}