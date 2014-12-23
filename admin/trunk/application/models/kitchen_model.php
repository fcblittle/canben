<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 厨房模型
 */
class Kitchen_model extends CI_Model {

    private $table = '`foodcar_kitchen`';

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
            
            'isDeleted' => null,
            'order'  => 'foodcar_kitchen.id DESC'
        ), $params);
        $result = array();
        $conds = '';
        if ($params['isDeleted'] !== null) {
            $conds .= " AND foodcar_kitchen.is_deleted = {$params['isDeleted']}";
        }
        $sql = "SELECT  foodcar_kitchen.* ,foodcar_diner_city.name as cname"
            . " FROM foodcar_kitchen ,foodcar_diner_city  "
            . " WHERE foodcar_kitchen.city_id=foodcar_diner_city.id {$conds}"
            . " ORDER BY {$params['order']}";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum){
            $result = $query->result();
        }
        return $result;
    }

    /**
     * 获取全部
     * @return array
     */
    public function get_all() {
        $return = array();
        $query = $this->db->get($this->table);
        $rownum = $query->num_rows();
        if($rownum) {
            $result = $query->result();
            foreach ($result as & $v) {
                $return[$v->id] = $v;
            }
        }
        return $return;
    }

    /**
     * 根据city_id  获取列表
     *
     * @access public
     * @return mixed
     */
    public function get_kitchen($city_id) {
        $sql = "SELECT  * "
            . " FROM foodcar_kitchen "
            . " WHERE city_id={$city_id } and is_deleted = 0"
            . " ORDER BY foodcar_kitchen.id DESC ";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

}