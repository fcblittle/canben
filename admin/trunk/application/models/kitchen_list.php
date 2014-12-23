<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 版本模型
 * 
 * @author sj
 * @package version_model
 */
class Kitchen_list extends CI_Model {

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
    public function getDailyList($params = array()) {
        $params = array_merge(array(
            'start'       => null,
            'end'         => null,
            'fields'      => '*',
            'order'       => 'date DESC,updated DESC',
            'offset'      => 0,
            'limit'       => 20,
            'sum'         => true
        ), $params);
        $result = array(
            'total' => null,
            'list'  => array()
        );
        $conds = '';
        $binds = array();
        if ($params['start'] !== null) {
            $conds .= " AND created >= ?";
            $binds[] = $params['start'];
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_kitchen_list_daily`"
            . " WHERE 1 {$conds}"
            . " ORDER BY {$params['order']}"
            . " LIMIT {$params['offset']},{$params['limit']}";
        $query = $this->db->query($sql, $binds);
        if ($query->num_rows() > 0) {
            $result['list'] = $query->result();
        }
        if ($params['sum']) {
            $sqlTotal =
                  " SELECT COUNT(id) AS count"
                . " FROM `foodcar_kitchen_list_daily`"
                . " WHERE 1 {$conds}";
            $query = $this->db->query($sqlTotal, $binds);
            $result['total'] = $query->row()->count;
        }

        return $result;
    }

    /**
     * 获取单个日清单
     * @param array $params
     */
    public function getDailyItem($params = array()) {
        $item = array();
        $params = array_merge(array(
            'fields' => '*'
        ), $params);
        $conds = '';
        $binds = array();
        if ($params['start'] !== null) {
            $conds .= ' AND created >= ?';
            $binds[] = $params['start'];
        }
        if ($params['end'] !== null) {
            $conds .= ' AND created <= ?';
            $binds[] = $params['end'];
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_kitchen_list_daily`"
            . " WHERE 1 {$conds}";
        $query = $this->db->query($sql, $binds);
        if ($query->num_rows()) {
            $item = $query->result();
        }
        return $item;
    }
}