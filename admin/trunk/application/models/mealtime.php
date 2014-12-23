<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用餐时间段模型
 * 
 * @author sj
 * @package version_model
 */
class Mealtime extends CI_Model {

    private $table = '`foodcar_mealtime`';

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
            'order'  => 'id DESC'
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

    /**
     * 获取全部
     * @param array $params
     * @return array
     */
    public function get_all($params = array()) {
        $params = array_merge(array(
            'fields'    => '*',
            'isDeleted' => null,
            'order'     => 'id DESC'
        ), $params);
        $items = array();
        $conds = '';
        if ($params['isDeleted'] !== null) {
            $conds .= " AND is_deleted = " . (int) $params['isDeleted'];
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_mealtime`"
            . " WHERE 1 {$conds}"
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