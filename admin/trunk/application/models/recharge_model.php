<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 提现模型
 */
class Recharge_model extends CI_Model {

    private $table = '`foodcar_recharge`';


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
            'fields'  => 'r.*,
                          u.id as uid,
                          u.nickname',
            'status'  => null,
            'name'    => '',
            'deleted' => false,
            'order'   => 'r.id DESC',
            'limit'   => 20,
            'page'    => 0,
        ), $params);
        $list = array();
        $conds = '';
        //状态
        if ($params['status'] !== null) {
            $conds .= " AND r.status = {$params['status']}";
        }
        //用户名
        if ($params['name'] !== '') {
            $conds .= " AND u.nickname LIKE '%{$params['name']}%'";
        }
        //手机号
        if ($params['mobile'] !== '') {
            $conds .= " AND r.mobile = '{$params['mobile']}'";
        }
        //流水号
        if ($params['order_no'] !== '') {
            $conds .= " AND r.order_no = '{$params['order_no']}'";
        }
        
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_recharge` r , `foodcar_userinfo` u"
            . " WHERE r.user_id=u.id   {$conds}"
            . " ORDER BY {$params['order']}"
            . " LIMIT {$params['page']},{$params['limit']}";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum) {
            $list = $query->result();
        }
        $counter = "SELECT COUNT(r.id) AS count"
            . " FROM `foodcar_recharge` r , `foodcar_userinfo` u"
            . " WHERE r.user_id=u.id {$conds}";
        $query = $this->db->query($counter);
        $count = $query->row()->count;

        return array(
            'list' => $list,
            'total' => $count
        );

        /*$result = array();
        $query = $this->db->get($this->table);
        $rownum = $query->num_rows();
        if($rownum) {
            $result = $query->result();
        }
        return $result;
        */
    }
}