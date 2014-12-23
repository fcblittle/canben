<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 反馈模型
 *
 * @author 健
 * @package Feedback_model
 */
class Feedback_model extends CI_Model {

    //构造
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 添加反馈
     *
     * @access public
     * @param int $uid
     * @param string $content
     * @return void
     */
    public function add_feedback($data) {
        $this->db->insert("foodcar_feedback",$data);
    }

     /**
     * 获取反馈列表
     *
     * @access public
     * @return mixed
     */
    public function get_list($params =array()) {
        $params = array_merge(array(
            'fields'  =>'*' ,
            'limit'   => 20,
            'page'    => 0,
        ), $params);
        
        $result = array();
        $sql = "SELECT {$params['fields']}"
            . " FROM foodcar_feedback"
            . " WHERE 1  order by insert_time  desc"
            . " LIMIT {$params['page']},{$params['limit']}";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum) {
           $list = $query->result();
        }
         $counter = "SELECT COUNT(id) AS count"
            . " FROM foodcar_feedback  "
            . " WHERE 1  ";
        $query = $this->db->query($counter);
        $count = $query->row()->count;

        return array(
            'list' => $list,
            'total' => $count
        );
    }

}