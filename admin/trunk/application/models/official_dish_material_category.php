<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 版本模型
 * 
 * @author sj
 * @package version_model
 */
class Official_dish_material_category extends CI_Model {

    private $table = '`foodcar_official_dish_material_category`';

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
            'fields' => '*',
            'isDeleted' => null,
            'order'  => 'weight ASC,id DESC'
        ), $params);
        $result = array();
        $conds = '';
        if ($params['isDeleted'] !== null) {
            $conds .= " AND is_deleted = {$params['isDeleted']}";
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM {$this->table}"
            . " WHERE 1 {$conds}"
            . " ORDER BY {$params['order']}";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum){
            $result = $query->result();
        }
        return $result;
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
            . " FROM {$this->table}"
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
}