<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 提报模型
 *
 * @author 健
 * @package Fund_model
 */
class Fund_model extends CI_Model {
    //构造
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 检验当天是否提报过
     * @author  liuyunxia
     */
    public function check_fund($diner_id){
        //根据餐车id 获取时候提报
        $time = date('Y-m-d',time());
        $stime = strtotime($time);
        $etime = $stime + 16*60*60 ;
        $sql = "select * from foodcar_order_offline where diner_id =".$diner_id." 
                and created between $stime and $etime";
        $query = $this->db->query($sql);
        $rows = $query->num_rows();
        return $rows;
    }

     /**
     * 添加提报
     * @author  liuyunxia
     */
    public function addOrderOffline($diner_id, $revision_id, $number, $total, $staff_id){
        //获取账户资金信息
        $sql = "select * from foodcar_staff_fund where staff_id =".$staff_id." 
                and type = 1";
        $query = $this->db->query($sql);
        $rows = $query->row();
        $staff_fund_id = $rows->id;
        $balance = $rows->balance;
        //准备资金变动数据
        $data['staff_fund_id'] = $staff_fund_id;
        $data['typeId'] = 5;
        $data['add_or_plus'] = '-1';
        $data['account'] = $total;
        $data['amount'] = $balance - $total;
        $data['created'] = time();
        $result = $this->db->insert('foodcar_staff_fund_variation',$data);
        //修改账户余额
        $sql1 = "update foodcar_staff_fund set balance = ".$data['amount']." 
                 where staff_id =".$staff_id." and type = 1";
        $query = $this->db->query($sql1);
        //准备提报数据
        foreach ($revision_id as $key => $value) {
            $item['diner_id'] = $diner_id;
            $item['dish_reversion_id'] = $reversion_id[$key];
            $item['count'] = $number[$key];
            $item['created'] = time();
            $res = $this->db->insert('foodcar_order_offline',$item);
        }
        return $res;
    }

    /**
     * 获取商户资金管理明细列表
     * @author  fcblittle
     */
    public function getMerchantList($params = array())
    {
        $params = array_merge(array(
            'fields'  => 'a.*, b.merchant_name',
            'order'   => 'a.id DESC',
            'keywords' => '',
            'limit'   => 20,
            'page'    => 0,
        ), $params);
        $list = array();
        $conds = '';
        //商户名称
        if ($params['keywords'] !== '') {
            $conds .= " AND b.merchant_name LIKE '%{$params['keywords']}%'";
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_merchant_fund` AS a"
            . " INNER JOIN `foodcar_merchant` AS b ON a.merchant_id = b.id"
            . " WHERE 1 {$conds}"
            . " ORDER BY {$params['order']}"
            . " LIMIT {$params['page']},{$params['limit']}";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum) {
            $list = $query->result();
        }
        $counter = "SELECT COUNT(a.id) AS count"
            . " FROM `foodcar_merchant_fund` AS a"
            . " INNER JOIN `foodcar_merchant` AS b ON a.merchant_id = b.id"
            . " WHERE 1 {$conds}";
        $query = $this->db->query($counter);
        $count = $query->row()->count;

        return array(
            'list' => $list,
            'total' => $count
        );
    }

    /**
     * 获取经营者资金管理明细列表
     * @author  fcblittle
     */
    public function getManagerList($params = array())
    {
        $params = array_merge(array(
            'fields'        => 'a.*, b.realname, c.diner_name, d.merchant_name',
            'order'         => 'a.id DESC',
            'manager_name'  => '',
            'diner_name'    => '',
            'merchant_name' => '',
            'limit'         => 20,
            'page'          => 0,
        ), $params);
        $list = array();
        $conds = '';
        // 经营者名称
        if ($params['manager_name'] !== '') {
            $conds .= " AND b.realname LIKE '%{$params['manager_name']}%'";
        }
        // 餐车名称
        if ($params['diner_name'] !== '') {
            $conds .= " AND c.diner_name LIKE '%{$params['diner_name']}%'";
        }
        // 商户名称
        if ($params['merchant_name'] !== '') {
            $conds .= " AND d.merchant_name LIKE '%{$params['merchant_name']}%'";
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_staff_fund` AS a"
            . " INNER JOIN `biz_staff` AS b ON a.staff_id = b.id"
            . " INNER JOIN `foodcar_diner` AS c ON b.diner_id = c.id"
            . " INNER JOIN `foodcar_merchant` AS d ON b.merchant_id = d.id"
            . " WHERE b.role = 1 {$conds}"
            . " ORDER BY {$params['order']}"
            . " LIMIT {$params['page']},{$params['limit']}";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum) {
            $list = $query->result();
        }
        $counter = "SELECT COUNT(a.id) AS count"
            . " FROM `foodcar_staff_fund` AS a"
            . " INNER JOIN `biz_staff` AS b ON a.staff_id = b.id"
            . " INNER JOIN `foodcar_diner` AS c ON b.diner_id = c.id"
            . " INNER JOIN `foodcar_merchant` AS d ON b.merchant_id = d.id"
            . " WHERE b.role = 1 {$conds}";
        $query = $this->db->query($counter);
        $count = $query->row()->count;

        return array(
            'list' => $list,
            'total' => $count
        );
    }

    /**
     * 获取资金变动类型表
     * @author  fcblittle
     */
    public function getVariationType()
    {
        $sql = "SELECT id, name FROM `foodcar_fund_variation_type`";
        $query = $this->db->query($sql);
        $result = $query->result();
        $tmp = array();
        foreach ($result as $key => $value) {
            $tmp[(int)$value->id] = $value->name;
        }
        return $tmp;
    }

    /**
     * 获取某商户资金管理明细列表
     * @author  fcblittle
     */
    public function getMerchantDetail($params = array())
    {
        $params = array_merge(array(
            'fields'  => '*',
            'order'   => 'created DESC, id DESC',
            'merchant_id' => 0,
            'type'    => 'wallet',
            'date'    => null,
            'variationtype' => -1,
            'limit'   => 20,
            'page'    => 0,
        ), $params);
        $list = array();
        $conds = '';
        if ($params['merchant_id']) {
            $conds .= " AND merchant_id = {$params['merchant_id']}";
        }
        if ($params['type']) {
            $conds .= " AND accountType = '{$params['type']}'";
        }
        if ($params['date']) {
            $begin = strtotime($params['date']);
            $end = $begin + 86399;
            $conds .= " AND created BETWEEN {$begin} AND {$end}";
        }
        if ($params['variationtype'] != -1) {
            $conds .= " AND variationTypeId = {$params['variationtype']}";
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_merchant_fund_variation`"
            . " WHERE 1 {$conds}"
            . " ORDER BY {$params['order']}"
            . " LIMIT {$params['page']},{$params['limit']}";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum) {
            $list = $query->result();
        }
        $counter = "SELECT COUNT(id) AS count"
            . " FROM `foodcar_merchant_fund_variation`"
            . " WHERE 1 {$conds}";
        $query = $this->db->query($counter);
        $count = $query->row()->count;

        return array(
            'list' => $list,
            'total' => $count
        );
    }

    /**
     * 获取某经营者资金管理明细列表
     * @author  fcblittle
     */
    public function getManagerDetail($params = array())
    {
        $params = array_merge(array(
            'fields'  => '*',
            'order'   => 'created DESC, id DESC',
            'staff_id' => 0,
            'type'    => 'wallet',
            'date'    => null,
            'variationtype' => -1,
            'limit'   => 20,
            'page'    => 0,
        ), $params);
        $list = array();
        $conds = '';
        if ($params['staff_id']) {
            $conds .= " AND staff_id = {$params['staff_id']}";
        }
        if ($params['type']) {
            $conds .= " AND accountType = '{$params['type']}'";
        }
        if ($params['date']) {
            $begin = strtotime($params['date']);
            $end = $begin + 86399;
            $conds .= " AND created BETWEEN {$begin} AND {$end}";
        }
        if ($params['variationtype'] != -1) {
            $conds .= " AND variationTypeId = {$params['variationtype']}";
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_staff_fund_variation`"
            . " WHERE 1 {$conds}"
            . " ORDER BY {$params['order']}"
            . " LIMIT {$params['page']},{$params['limit']}";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum) {
            $list = $query->result();
        }
        $counter = "SELECT COUNT(id) AS count"
            . " FROM `foodcar_staff_fund_variation`"
            . " WHERE 1 {$conds}";
        $query = $this->db->query($counter);
        $count = $query->row()->count;

        return array(
            'list' => $list,
            'total' => $count
        );
    }
}
