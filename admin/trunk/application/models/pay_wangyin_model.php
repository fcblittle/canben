<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 支付模型
 *
 * @author 再晨
 * @package Pay_wangyin_model
 */
class Pay_wangyin_model extends CI_Model {
    function __construct(){
        parent::__construct();
    }
    /**
     * 插入交易记录
     *
     * @access public
     * @param mixed $array
     * @return void
     */
    public function add_transaction($data){
        $arr_exist = count(array_filter($data));
        if ($arr_exist) {
            //$this->db->insert('foodcar_transaction', $data); 
            $ins_sql = "insert into foodcar_transaction(merchant,terminal,
                                                        sign,type,order_no,
                                                        money,currency,reason,
                                                        time,return_status,return_code,return_desc)
                        values('".$data['merchant']."','".$data['terminal']."',
                               '".$data['sign']."','".$data['type']."','".$data['order_no']."',
                               '".$data['money']."','".$data['currency']."','".$data['reason']."',
                               '".$data['time']."','".$data['return_status']."','".$data['return_code']."','".$data['return_desc']."')";
            $query = $this->db->query($ins_sql);
            $lastid = $this->db->insert_id();
            if ($lastid) {
                $str = substr($data['order_no'],0,1);
                switch ($str) {
                    case 'C':
                        //餐本支付订单
                        $numbers = range (10,100);
                        shuffle ($numbers); //对数组进行随机排序
                        $refix = array_slice($numbers,1,3); //取数组前3个元素
                        $payment_pwd = implode("", $refix);
                        $payment_pwd = $this->encrypt->encode($payment_pwd);
                        //更新支付验证码 并 改变订单状态 为 已支付
                        $upd_sql = "update foodcar_order 
                                    set status = '3', 
                                        payment_pwd = '".$payment_pwd."' ,
                                        time_paid = ".time()."
                                    where orderno = '".$data['order_no']."'";
                        //
                        $sel_food = "select fo.food_id,o.store_id
                                     from foodcar_food_order fo 
                                     left join foodcar_order o 
                                     on fo.order_id = o.id
                                     where o.orderno = '".$data['order_no']."'
                                    ";
                        $query_food = $this->db->query($sel_food);
                        $res_food = $query_food->result_array();
                        $store_id = 0;
                        foreach ($res_food as $row){
                            $food_arr[] = $row['food_id'];
                            $store_id = $row['store_id'];
                        };
                        $food_str = implode(",", $food_arr);                        
                        $upd_eatnum = " update foodcar_official_dish set eat_num = eat_num + 1 where id in (".$food_str.") ";
                        $this->db->query($upd_eatnum); 
                        //
                        $push_type = 2;
                        $tag_name = "diner".$store_id;
                        $message_key = "msg_key";
                        $messages = '{ 
                                    "title": "您有一个新订单",
                                    "description": "您有一个新订单已支付请注意给用户准备好餐品！",
                                    "notice_type":1
                                    }';
                        $user_id = null;
                        $channel_id = null;
                        break;
                    case 'D':
                        //联合食通支付订单
                        # code...
                        break;
                    case 'R':
                        //餐本充值订单
                        $upd_sql = "update foodcar_recharge 
                                    set status = '2' ,
                                        time = ".time()."
                                    where order_no = '".$data['order_no']."'";
                        //更新消费者余额
                        $sql_uid = " select user_id from foodcar_recharge where order_no = '".$data['order_no']."'";
                        $query_uid = $this->db->query($sql_uid);
                        $uid = $query_uid->row()->user_id;
                        $recharge_money = $data['money']/100;
                        $sql_upd = "UPDATE foodcar_userinfo SET account = account + ".$recharge_money." WHERE id = '".$uid."'";
                        $query_upd = $this->db->query($sql_upd);
                        break;
                    case 'W':
                        //提现
                        # code...
                        break;
                    default:
                        # code...
                        break;
                }
                $query = $this->db->query($upd_sql);
                /*if($this->db->affected_rows()){
                    if (isset($push_type)) {
                        $this->load->model('merchant_push', 'push', true);
                        $this->push->pushMessage_android($push_type,$tag_name,$user_id,$channel_id,$messages,$message_key);
                        //
                        $sql_staff_ios = "SELECT baidu_push_userid,baidu_push_channelid 
                                          FROM biz_staff 
                                          WHERE diner_id = '".$store_id."' AND device_type = '4'";
                        $query_staff_ios = $this->db->query($sql_staff_ios);
                        $res_staff_ios = $query_staff_ios->result_array();
                        $message = "您有一个新订单已支付请及时给用户准备好餐品！";
                        foreach ($res_staff_ios as $row){
                            $this->push->pushMessage_ios($row['baidu_push_userid'],$row['baidu_push_channelid'],$message,$message_key);
                        }
                    }*/
                    return 1;
                }else{
                    return -1;
                }
            }else{
                return -2;
            }
        }else{
            return -3;
        }
    }
}