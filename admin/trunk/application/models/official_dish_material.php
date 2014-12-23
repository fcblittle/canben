<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 版本模型
 * 
 * @author sj
 * @package version_model
 */
class Official_dish_material extends CI_Model {

    //构造
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 根据城市id 获取原料列表
     * 
     * @access public
     * @return mixed
     */
    public function get_materialcity($city_id) {        
        $sql = " SELECT d.*, "
              . "      cf.city_id"
              . " FROM `foodcar_official_dish_material` d"
              . " LEFT JOIN `foodcar_city_material` cf ON cf.material_id = d.id "
              . " WHERE cf.city_id = {$city_id} AND d.status <> 2 "
              . " ORDER BY d.time_updated DESC,d.id DESC";
        $query = $this->db->query($sql);
        $rownum = $query->result_array();
        return $rownum;
    }    
    
    /**
     * 根据城市id获取列表
     *
     * @access public
     * @return mixed
     */
    public function get_list_by_cid($params = array(), $cid) {
        $params = array_merge(array(
                'fields' => '*',
                'deleted' => null,
        ), $params);
        $list = array();
        $conds = '';
        if ($params['deleted'] === true) {
            $conds .= " AND status = 2";
        }
        if ($params['deleted'] === false) {
            $conds .= " AND status <> 2";
        }
        $items = array();
        $sql = "SELECT a.* "
        . " FROM `foodcar_official_dish_material` as a"
        . " LEFT JOIN foodcar_city_material as b ON a.id = b.material_id"
                . " WHERE 1 {$conds} AND b.city_id = {$cid}";
                $query = $this->db->query($sql);
                if($query->num_rows() > 0) {
                $result = $query->result();
                foreach ($result as & $v) {
                $items[$v->id] = $v;
                }
                }
                //print_r($items);die;
                return $items;
    }

    /**
     * 获取列表
     * 
     * @access public
     * @return mixed
     */
    public function get_list($params = array()) {
        $params = array_merge(array(
            'fields' => '*',
            'status' => null,
            'name'    => '',
            'deleted' => false,
            'limit'  => 20,
            'page'   => 0 
        ), $params);
         $list = array();
        $conds = '';
        if ($params['status'] !== null) {
            $conds .= " AND status = {$params['status']}";
        }
        if ($params['deleted'] === false) {
            $conds .= " AND status <> 2";
        }
        if ($params['deleted'] === true) {
            $conds .= " AND status = 2";
        }
        //原料名
        if ($params['name'] !== '') {
            $conds .= " AND name LIKE '%{$params['name']}%'";
        }
        //厨房
        if ($params['kitchen_id'] !== '') {
            $sql="select id from foodcar_kitchen where name like '%{$params['kitchen_id']}%' ";
            $query = $this->db->query($sql);
            $rownum = $query->num_rows();
            if($rownum) {
                $ids=array();
                foreach($query->result() as $row)
                {
                    $ids[]= $row->id;
                }
                $subids = implode(',', $ids);
                $conds .= " AND kitchen_id  = '{$subids}' ";
            }else{
                $conds .= " AND kitchen_id  = 'null' ";
            }
           
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_official_dish_material`"
            . " WHERE 1 {$conds}"
            . " LIMIT {$params['page']},{$params['limit']}";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum) {
            $list = $query->result();
        }
        $counter = "SELECT COUNT(id) AS count"
            . " FROM `foodcar_official_dish_material`"
            . " WHERE 1 {$conds}";
        $query = $this->db->query($counter);
        $count = $query->row()->count;

        return array(
            'list' => $list,
            'total' => $count
        );
    }

    public function get_all($params = array()) {
        $params = array_merge(array(
            'fields' => '*',
            'deleted' => null,
        ), $params);
        $list = array();
        $conds = '';
        if ($params['deleted'] === true) {
            $conds .= " AND status = 2";
        }
        if ($params['deleted'] === false) {
            $conds .= " AND status <> 2";
        }
        $items = array();
        $sql = "SELECT {$params['fields']} "
            . " FROM `foodcar_official_dish_material`"
            . " WHERE 1 {$conds}";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as & $v) {
                $items[$v->id] = $v;
            }
        }
        return $items;
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
            . " FROM `foodcar_official_dish_material`"
            . " WHERE id IN({$ids})";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as & $v) {
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
            . " FROM `foodcar_official_dish_material_revision`"
            . " WHERE id IN({$ids})";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as & $v) {
                $items[$v->id] = $v;
            }
        }
        return $items;
    }

    /**
     * 获取items
     *
     * @access public
     * @return mixed
     */
    public function get_item($ids) {
        $ids = is_array($ids) ? implode(',', $ids) : $ids;
        $items = array();
        $sql = "SELECT  fo.id,fo.category_id,fo.name,
                        fo.unit,fo.spec,fo.price,fo.remark,fo.status,
                        foodcar_kitchen.name as kitchen_name"
            . " FROM `foodcar_official_dish_material` fo 
                left join foodcar_kitchen on foodcar_kitchen.id=fo.kitchen_id "
            . " WHERE fo.id IN({$ids})";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as & $v) {
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
        $sql = "INSERT INTO `foodcar_official_dish_material_revision`"
            . " (source_id,category_id,kitchen_id,name,unit,spec,"
            . " price,remark,status,time_created)"
            . " SELECT id,category_id,kitchen_id,name,unit,spec,"
            . " price,remark,status,time_updated"
            . " FROM `foodcar_official_dish_material`"
            . " WHERE id = {$id}";
        $q = $this->db->query($sql);
        if ($this->db->affected_rows()) {
            return $this->db->insert_id();
        }
        return false;
    }
}