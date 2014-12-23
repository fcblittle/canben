<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 退款模型
 */
class Refund_model extends CI_Model {

    private $table = '`foodcar_apply_refund`';


    //构造
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取提现列表
     *
     * @access public
     * @return mixed
     */
    public function get_list($params = array()) {
        $params = array_merge(array(
            'status' => null,
            'fields' => '*'
        ), $params);
        $conds = '';
        if ($params['status'] !== null) {
            if (is_array($params['status'])) {
                $status = implode(',', $params['status']);
                $conds .= " AND status IN ({$status})";
            } else {
                $conds .= " AND status = {$params['status']}";
            }
        }
        $result = array();
        $sql = "SELECT {$params['fields']}"
            . " FROM {$this->table}"
            . " WHERE 1 {$conds} order by time_created  desc";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum) {
            $result = $query->result();
        }
        return $result;
    }

    /**
     * 获取同意的提现列表
     *
     * @access public
     * @return mixed
     */
    public function get_consent_list() {
        $result = array();
        $query = $this->db->get_where($this->table, array('status' => 2),10);
        $rownum = $query->num_rows();
        if($rownum) {
            $result = $query->result();
        }
        return $result;
    }
}