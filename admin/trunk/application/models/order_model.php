<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 订单模型
 *
 * @author 健
 * @package Order_model
 */
class Order_model extends CI_Model {
    //构造
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 得到订单列表 #####
     *
     * @access public
     * @param mixed $uid
     * @param mixed $status 订单状态,1:完成 2未支付 3已支付  4.已退款 5.已确认
     * @param int $rows
     * @param int $offset
     * @return void
     */
    public function get_order_list($uid, $status=2, $rows=20, $offset=0) {
        if ($status==3) {
            $sql = " SELECT
                        `o`.`id` as order_id, `o`.`orderno`,
                        `o`.`store_id`, `o`.`store_name`,
                        `o`.`order_amount`, `o`.`order_count`, `o`.`payment_pwd`,
                        `o`.`time_paid`, `o`.`status`,`o`.`order_type`
                     FROM (`foodcar_order` o) 
                     WHERE `o`.`user_id` = '".$uid."' AND (`o`.`status` = '3' OR `o`.`status` = '5')
                     ORDER BY `o`.`time_paid` desc LIMIT ".$offset.",".$rows."";
            $query = $this->db->query($sql);
            $res = $query->result_array();
            $num = count($res);
            for ($i=0; $i <$num ; $i++) {
                if(!empty($res[$i]['payment_pwd'])){
                    $res[$i]['payment_pwd'] = $this->encrypt->decode($res[$i]['payment_pwd']);
                }else{
                    $res[$i]['payment_pwd'] = "";
                }
            }
        } else {
            if ($status==2) {
                $time_str = "insert_time";
            } else {
                $time_str = "time_paid";
            }
            /*$this->db->select('id as order_id,orderno,store_id,
                               store_name,order_amount,order_count,
                               $time_str');
            $this->db->order_by("$time_str", "desc");
            $query = $this->db->get_where('foodcar_order', array('user_id'=>$uid,"status"=>$status), $rows, $offset);*/
            $sql = " select id as order_id,orderno,store_id,
                            store_name,order_amount,order_count,".$time_str." ,order_type
                     from foodcar_order 
                     where user_id = '".$uid."' and status = '".$status."' 
                     order by ".$time_str." desc limit ".$offset.",".$rows." ";
            $query = $this->db->query($sql);
            $res = $query->result_array();
        }
        //返回
        return $res;
    }
    /**
     * 得到订单详情
     *
     * @access public
     * @return void
     */
    public function get_order_info($order_id) {
        $this->db->select('id as order_id,orderno,
                           store_id,store_name,tag,
                           store_address,user_id,
                           order_person,order_person_tel,
                           delivery_address,expect_arrival_time,
                           delivery_methods,pay_methods,
                           order_amount as order_total,remark,
                           insert_time,payment_pwd,order_type
                         ');
        $this->db->order_by("insert_time", "desc");
        $query = $this->db->get_where('foodcar_order', array('id' => $order_id));
        //返回
        $order_info =  $query->row_array();
        //订单号不存在
        if ( !isset ( $order_info['order_id'] ) ) {
            return array();
        }
        //解密验证码
        if (isset($order_info['payment_pwd']) && !empty($order_info['payment_pwd'])) {
            $order_info['payment_pwd'] = $this->encrypt->decode($order_info['payment_pwd']);
        }
        if($order_info['tag'] == '1'){
            $this->db->select('store_tel')->from('foodcar_store')->where('id', $order_info['store_id']);
            $query = $this->db->get();
            $res = $query->row_array();
            $order_info['tel'] = $res['store_tel'];
        }elseif($order_info['tag'] == '2'){
	    $conds = array('diner_id' => $order_info['store_id'], 'role' => 1);
            $this->db->select('username')->from('biz_staff')->where($conds);
            $query = $this->db->get();
            $res = $query->row_array();
            $order_info['tel'] = $res['username'];
        }
        //
        $this->db->select('food_id as dishid,food_name as name,
                           num as number,unit_price as price,
                           count as total,img');
        $query2 = $this->db->get_where('foodcar_food_order', array('order_id' => $order_id));
        $order_info['foods'] = $query2->result_array();
        return $order_info;
    }
    /**
     * 得到订单列表总数
     *
     * @access public
     * @param mixed $uid
     * @return void
     */
    public function get_order_list_total($uid, $status=null) {
        //设置
        $set = array();
        //拼接语句
        $sql = 'select count(id) as total from foodcar_order where user_id = ? ';
        $set[] = $uid;
        if ( isset($status) ) {
            $sql .= ' and status = ?';
            $set[] = $status;
            if ($status == 3) {
                $sql .= ' or status = ?';
                $set[] = 5;
            }
        }
        //查询
        $query = $this->db->query($sql, $set);
        //返回总数
        return $query->row()->total;
    }
    /**
     * 添加订单
     *
     * @access public
     * @param mixed $uid
     * @param mixed $diner_id
     * @param array $foods
     * @return void
     */
    public function add_order($uid,$diner_id,$foods,$delivery_address="",
                              $remark="",$order_person,$order_person_tel,
                              $delivery_methods,$pay_methods,$order_type,
                              $expect_arrival_time) {
        date_default_timezone_set ('PRC');
        $chk_arr = count(array_filter($foods));
        if(!$chk_arr){
            return false;
        }
        //下单时间
        $time = time();
        //计算订单过期时间
        //$hour = date('H', $time);
        //$order_exp_time = $hour >= 13 ? strtotime( date('Y-m-d', $time) . ' 19:00' ) : strtotime( date( 'Y-m-d', $time ) . ' 13:00' );
        //载入store模型
        $this->load->model('store_model', 'store', true);
        $numbers = range (10,99);
        shuffle ($numbers); //对数组进行随机排序
        $refix = array_slice($numbers,1,2); //
        $refix = implode("", $refix);
        $data_order['orderno'] = "C".$refix.date('YmdHis');//C+流水号 C=consume消费
        $data_order['store_id'] = $diner_id;
        $table = "foodcar_diner" ;
        $where = " id = '".$diner_id."'";
        $data_order['store_name'] = $this->store->get_valName("diner_name",$table,$where);
        $data_order['tag'] = "2";
        //$data_order['store_address'] = $this->store->get_valName("address",$table,$where);
        $data_order['user_id'] = $uid;
        $data_order['order_person'] = $order_person;
        $data_order['order_person_tel'] = $order_person_tel;
        $data_order['delivery_address'] = $delivery_address;
        $data_order['order_expires_time'] = "0";
        $data_order['expect_arrival_time'] = (empty($expect_arrival_time) || is_null($expect_arrival_time)) ? 0 : $expect_arrival_time;
        $data_order['delivery_methods'] = $delivery_methods;
        $data_order['pay_methods'] = $pay_methods;
        $data_order['status'] = 2;
        $data_order['remark'] = $remark;
        $data_order['insert_time'] = time();
        $data_order['order_type'] = $order_type;//1:普通订单；2:预订订单
        $this->db->insert('foodcar_order', $data_order);
        $order_id = $this->db->insert_id();
        if ($order_id) {
            //合计
            $total = 0;
            $total_count = 0;
            //载入food模型
            $this->load->model('food_model', 'food', true);
            foreach ( $foods as $row ) {
                $data_orderfood['order_id'] = $order_id;
                $data_orderfood['diner_id'] = 0;
                $data_orderfood['food_id'] = $row['food_id'];
                $data_orderfood['food_name'] = $row['food_name'];
                $data_orderfood['img'] = $this->food->get_img_by_id($row['food_id']);
                $data_orderfood['num'] = $row['num'];
                $data_orderfood['supply_price'] = (($row['supply_price']=='null') || empty($row['supply_price'])) ?  0.00 : $row['supply_price'];//供货价
                $data_orderfood['unit_price'] = $row['unit_price'];//销售价
                $count = $row['num'] * $row['unit_price'];
                $total += $count;
                $total_count += $row['num'];
                $data_orderfood['count'] = $count;
                $this->db->insert('foodcar_food_order', $data_orderfood);
                //更新菜品被吃过的次数
                $this->food->update_eat_num($row['food_id']);
            }
            //更新订单总金额
            $this->update_order_total($order_id, $total ,$total_count);
        }
        return $order_id;
    }
    /**
     * 更新订单总金额
     *
     * @access public
     * @param mixed $order_id
     * @return void
     */
    public function update_order_total($order_id, $total,$total_count) {
        //设置
        $set = array();
        //拼接语句
        $sql = 'update foodcar_order set order_amount = ?,order_count = ? where id = ?';
        $set[] = $total;
        $set[] = $total_count;
        $set[] = $order_id;
        //执行
        $this->db->query($sql, $set);
        //返回总数
        return true;
    }
    /**
     * 删除作废订单
     *
     * @access public
     * @param mixed $uid
     * @param array $order_array
     * @return void
     */
    public function del_fail_order($uid, $order_id) {
        //设置
        $set = array();
        //语句
        $sql = "delete from foodcar_order where id = ? and user_id = ? and status = 2 ";
        $set[] = $order_id;
        $set[] = $uid;
        //删除订单
        $this->db->query($sql, $set);
        //---------删除订单菜品
        $affect_num = $this->db->affected_rows();
        if($affect_num){
            //语句
            $sql = "delete from foodcar_food_order where order_id = '".$order_id."'";
            //删除订单菜品
            $this->db->query($sql);
        }else{
            return false;
        }
        //返回
        return true;
    }
    /**
     * 判断根据用户uid获得订单信息
     *
     * @access public
     * @param mixed $uid
     * @return array $order_array
     */
    public function isExist_orderinfo_byuid($uid,$food_id){
        $this->db->select('foodcar_order.id');
        $this->db->from('foodcar_order');
        $this->db->join('foodcar_food_order', 'foodcar_order.id = foodcar_food_order.order_id');
        $this->db->where('foodcar_order.user_id', $uid);
        $this->db->where('foodcar_food_order.food_id', $food_id);
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num) {
            return $query->row()->id;
        }else{
            return 0;
        }
    }
    /**
     * 检查订单中的菜品是否售完
     *
     * @access public
     * @param mixed $food_id
     * @return void
     */
    public function check_foodsold($foods,$diner_id){
        $food_str = $food_err = "";
        $count_foods = count($foods);
        for ($i=0; $i < $count_foods; $i++) {
            $this->db->select('sold_out');
            $query = $this->db->get_where('foodcar_food_relation',
                                          array('food_id' => $foods[$i]['food_id'],
                                                'diner_id' =>$diner_id,
                                                'status'=>'1'));
            $num = $query->num_rows();
            if(($num) && ($query->row()->sold_out == 1)){
                $food_str .= $foods[$i]['food_id'].",";
            }elseif (!$num) {
                $food_err .= $foods[$i]['food_id'].",";
            }
        }
        if (strlen($food_str)) {
            return array("num"=>1,"str"=>$food_str);
        } elseif (strlen($food_err)) {
            return array("num"=>2,"str"=>$food_err);
        } else{
            return 3;
        }
    }
    /**
     * 获取多个item
     * @author kevin
     */
    public function get_items(array $orderNOs, $fields = '*') {
        $ids = array();
        foreach ($orderNOs as $v) {
            $ids[] = "'{$v}'";
        }
        $ids = implode(',', $ids);
        $items = array();
        $sql = "SELECT {$fields}"
            . " FROM `foodcar_order`"
            . " WHERE orderno IN ({$ids})";
        $q = $this->db->query($sql);
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $v) {
                $items[$v->orderno] = $v;
            }
        }
        return $items;
    }
    /**
     * 更新订单状态
     * @author zaichen
     */
    public function upd_order_status($order_id,$status){
        $data = array(
                       'status' => $status
                    );
        $this->db->where('id', $order_id);
        $this->db->update('foodcar_order', $data);
        if ($this->db->affected_rows()) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取该餐车的线上订单总额
     * @author zaichen
     */
    public function get_diner_orderlist($diner_id,$biz_id){
        //根据餐车id 获取线上订单总额
        $time = date('Y-m-d',time());
        $stime = strtotime($time);
        $etime = $stime + 16*60*60 ;
        $sql = "select sum(order_amount) as order_amount from foodcar_order where store_id =".$diner_id." 
                and tag = 2 and status = 2 and order_type = 1 
                and time_paid between $stime and $etime";
        $query = $this->db->query($sql);
        $res = $query->row();
        if ($res->order_amount) {
            $list['order_amount'] = $res->order_amount;
        } else {
            $list['order_amount'] = 0.00;
        }
        //获取店长钱包金额 
        $sql1 = "select balance from foodcar_staff_fund where staff_id = ".$biz_id." and type=1";
        $query1 = $this->db->query($sql1);
        $re = $query1->row();
        if ($re) {
           $list['balance'] = $re->balance;
        } else {
            $list['balance'] = 0.00;
        } 
        return $list; 
    }

}
