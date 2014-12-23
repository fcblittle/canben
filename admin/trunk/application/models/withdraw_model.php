<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 提现模型
 */
class Withdraw_model extends CI_Model {

    private $table = '`foodcar_user_withdrawals`';


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
    public function get_withdraw_list() {
        $result = array();
        $query = $this->db->get($this->table);
        $rownum = $query->num_rows();
        if($rownum) {
            $result = $query->result();
        }
        return $result;
    }

    public function get_list($params = array()) {
        $params = array_merge(array(
            'fields'  => 'w.*,
                          u.id as uid,
                          u.nickname',
            'status'  => null,
            'name'    => '',
            'deleted' => false,
            'order'   => 'w.id DESC',
            'limit'   => 20,
            'page'    => 0,
        ), $params);
        $list = array();
        $conds = '';
        //状态
        if ($params['status'] !== null) {
            $conds .= " AND w.status = {$params['status']}";
        }
        //用户名
        if ($params['name'] !== '') {
            $conds .= " AND u.nickname LIKE '%{$params['name']}%'";
        }
        //手机号
        if ($params['mobile'] !== '') {
            $conds .= " AND w.mobile = '{$params['mobile']}'";
        }
        //银行号
        if ($params['bank_account'] !== '') {
            $conds .= " AND w.bank_account = '{$params['bank_account']}'";
        }
        
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_user_withdrawals` w , `foodcar_userinfo` u"
            . " WHERE w.user_id=u.id   {$conds}"
            . " ORDER BY {$params['order']}"
            . " LIMIT {$params['page']},{$params['limit']}";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum) {
            $list = $query->result();
        }
        $counter = "SELECT COUNT(w.id) AS count"
            . " FROM `foodcar_user_withdrawals` w , `foodcar_userinfo` u"
            . " WHERE w.user_id=u.id {$conds}";
        $query = $this->db->query($counter);
        $count = $query->row()->count;

        return array(
            'list' => $list,
            'total' => $count
        );
    }
}