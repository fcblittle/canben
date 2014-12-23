<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 店小二模型
 *
 * @author 健
 * @package Staff_model
 * @author liuyunxia
 */
class Staff_model extends CI_Model {
//常量 盐值
    const salt = 'wooxqwla;fovgjvbwoswiq';
    //构造
    public function __construct()    {
        parent::__construct();
    } 
    //得到加密密码
    private function  password($string,$salt) {
      return  md5(hash('sha256',$string).$salt);
    }
     //店小二查询 
    public function  check_login($username,$pwd) {
        $sql = "SELECT staff.*, diner.diner_name, merchant.merchant_name
                FROM `biz_staff` AS staff
                LEFT JOIN `foodcar_diner` AS diner
                ON staff.diner_id = diner.id
                LEFT JOIN `foodcar_merchant` AS merchant
                ON staff.merchant_id = merchant.id
                WHERE staff.username = ? AND staff.status = 1";
        $result = $this->db->query($sql, array($username));
        $staff = $result->row();
        if (empty($staff) || ($staff->pass !== $this->password($pwd, $staff->salt))) {
          return false;
        }

        unset($staff->pass);
        unset($staff->salt);
        return $staff;
    }
    //修改店小二密码
    public function update_pwd($username, $pass,$salt){
        $set = array();
        $sql = 'UPDATE biz_staff SET pass = ? WHERE username = ?';
        $set[] = $this->password($pass,$salt);
        $set[] = $username;
        $this->db->query($sql, $set);
        return true;
    }
    // 获取订单
    public function get_order($order_id,$biz_id)
    {
      $sql = "SELECT id 
              FROM foodcar_order_give 
              WHERE order_id=$order_id 
              AND biz_id=$biz_id";
      $query = $this->db->query($sql);
      $num= $query->num_rows();
      if ($num) {
        return $query->row_array();
      } else {
        return false;
      }
    }

    //店小二添加我的订单
    public function add_order($order_id,$biz_id) {
        $order = $this->get_order($order_id,$biz_id);
        if (! empty($order)) {
          return 'exist';
        }

        $sql = "INSERT INTO  `foodcar_order_give`(order_id, biz_id,starttime)
                VALUES (?, ?, ?)";
        $set = array($order_id, $biz_id, time());

        $this->db->query($sql, $set);
        return $this->db->insert_id();
    }
    //店小二删除我的订单
    public function del_order($order_id,$biz_id) {
        $set = array();
        $set[]=$order_id;
        $set[]=$biz_id;
        $sql = "DELETE FROM foodcar_order_give 
                WHERE order_id=? AND biz_id=?";
        $query=$this->db->query($sql,$set);
        return $query;
    }
    //店小二管理配送 列表
    public function sel_biz_manage($merchant_id,$diner_id) {
        $this->db->select('foodcar_order.id, foodcar_order.orderno,
                           foodcar_order.delivery_address,foodcar_order.order_amount ,
                           foodcar_order.order_count, foodcar_order.status,
                           biz_staff.realname,biz_staff.username AS phone,
                           foodcar_order_give.starttime,foodcar_order_give.endtime ');
        $this->db->from('foodcar_order_give');
        $this->db->join('biz_staff', 'foodcar_order_give.biz_id = biz_staff.id');
        $this->db->join('foodcar_order', 'foodcar_order_give.order_id = foodcar_order.id');
        $this->db->where('biz_staff.merchant_id', $merchant_id); 
        $this->db->where('biz_staff.diner_id', $diner_id);
        $this->db->where('foodcar_order.delivery_methods',1);
        $this->db->where('biz_staff.role',2);
        $this->db->where('foodcar_order.status',1);
        $query = $this->db->get();     
        return $data=$query->result_array();
    }
    // 店小二管理自提列表
    public function  pickedup_manage($merchant_id,$diner_id) {
       $set = array();
       $set['merchant_id']=$merchant_id;
       $set['diner_id']=$diner_id;
       $sql = ' SELECT 
                    fo.id,fo.orderno,fo.delivery_address,
                    fo.order_amount ,fo.order_count,fo.status,
                    bs.realname,bs.phone,
                    fg.starttime,fg.endtime
                 FROM  foodcar_order_give fg
                 LEFT JOIN  biz_staff bs ON fg.biz_id=bs.id 
                 LEFT JOIN  foodcar_order fo ON fo.id=fg.order_id 
                 WHERE bs.merchant_id =? AND fo.status=1
                   AND bs.diner_id=? AND bs.role=2 and fo.delivery_methods=2 '; 
        $query = $this->db->query($sql,$set);
        $data = $query->result_array();
        return $data;
    }
    
    /**
     * 店小二 搜索
     * 
     * @return $data
     */
    public function  sel_biz_search($args) {

        extract($args);

        // var_dump($order_type);
        // var_dump($status);
        // var_dump($phone);
        // var_dump($delivery_methods);die;

        if ($delivery_methods == 2) { // 自提
          $query = $this->db
                      ->select("forder.*, user.mobile_phone AS default_phone_num")
                      ->from("`foodcar_order` AS forder")
                      ->join("`foodcar_order_give` AS give", "forder.id = give.order_id", "left")
                      ->join("`foodcar_userinfo` AS user", "forder.user_id = user.id", "left");

          // 订单类型
          if (! empty($order_type)) {
            $query->where('forder.order_type', $order_type);
          }
          // 订单状态
          if (! empty($status)) {
            $query->where('forder.status', $status);
          } else {
            $query->where("(forder.status = 3 OR forder.status = 5)");
          }
          // 电话号码搜索
          if (! empty($phone)) {
            $query->where("(forder.order_person_tel = '{$phone}' OR user.mobile_phone = '{$phone}')");
          }

          $query->where("forder.delivery_methods", 2);

          $data = $query->get()->result_array();
        } else {
          $query = $this->db
                       ->select("forder.*, user.mobile_phone AS default_phone_num, staff.username, staff.realname, give.starttime, give.endtime")
                       ->from("foodcar_order_give AS give")
                       ->join("foodcar_order AS forder", "forder.id = give.order_id", "left")
                       ->join("biz_staff AS staff", "give.biz_id = staff.id", "left")
                       ->join("foodcar_userinfo AS user", "forder.user_id = user.id", "left");

          // 店小二id
          if (! empty($biz_id)) {
            $query->where('staff.id', $biz_id);
          }
          // 订单状态
          if (! empty($status)) {
            $query->where('forder.status', $status);
          } else {
            $query->where("(forder.status = 3 OR forder.status = 5)");
          }
          // 电话号码搜索
          if (! empty($phone)) {
            $query->where("(forder.order_person_tel = '{$phone}' OR user.mobile_phone = '{$phone}')");
          }

          $query->where("forder.delivery_methods", 1);

          $data = $query->get()->result_array();

          foreach ($data as $key => $item) {
            $data[$key]['realname'] = ! empty($item['realname']) 
                                        ? $item['realname'] 
                                        : $item['username'];
          }
        }

        // 获取电话
        foreach ($data as $key => $item) {
          $data[$key]['phone'] = ! empty($item->order_person_tel)
                                    ? $item->order_person_tel
                                    : $item->mobile_phone;
        }

        return $data;
    }
    //店小二配送生成authorization验证
    public function biz_verify($order_id,$payment_pwd){
       $sql="SELECT  orderno,payment_pwd 
             FROM foodcar_order
             WHERE  id=".$order_id." ";
       $query = $this->db->query($sql);
       $data = $query->row();
       $food = $query->num_rows();
       if($food){
           $payment_pwd1 = $this->encrypt->decode($data->payment_pwd); 
           if($payment_pwd1==$payment_pwd)
           {
               $res_biz=$this->biz_updauth($data->payment_pwd,$order_id,$data->orderno);
               if($res_biz){
                  return 1;
               }else{
                  return "";
               }
           }else{
              return "";
           }
       }else{
         return "";
       }
    }
    //店小二配送确认修改
    private function biz_updauth($payment_pwd,$id,$orderno) {
        $set = array();
        $set['authorization']=$payment_pwd;//支付验证字符串
        $set['endtime']=time();
        $sql = " UPDATE foodcar_order_give 
                   SET  authorization =?,endtime=? 
                 WHERE  order_id = '". $id."' ";
        $query= $this->db->query($sql,$set);
        if($query){
            $sql1 = "UPDATE foodcar_order SET status= 1, insert_time = ".time()."  WHERE id = '".$id."' ";
            $quer= $this->db->query($sql1);
            $this->db->update('foodcar_apply_refund', array('status' => '4'), array('order_no' => $orderno));
            return $quer;
        }else{
            return "";
        }
    } 
    //店小二自提 确认订单生成authorization验证
    public function pickedup_getauth($id,$biz_id,$authorization){
       $sql=" SELECT  orderno,payment_pwd 
              FROM  foodcar_order 
              WHERE id = '".$id."'";
       $query = $this->db->query($sql);
       $data = $query->row();
       $num=$query->num_rows();
       if($num){
           $authorization1 = $this->encrypt->decode($data->payment_pwd);
           if($authorization1==$authorization){
               $res_pickup=$this->pickedup_updauth($data->payment_pwd,$id,$biz_id,$data->orderno);
               if($res_pickup){
                 return $authorization;
               }else{
                return "";
               }
               
           }else{
               return "";
           }
       }else{
        return "";
       }
    }
    //店小二自提确认修改
    private function pickedup_updauth($authorization,$id,$biz_id,$order_no) {
        $set = array();
        $set['authorization'] = $authorization;
        $set['order_id'] = $id;
        $set['biz_id'] = $biz_id;
        $set['endtime'] = time();
        $query= $this->db->insert('foodcar_order_give', $set);
        $sql = " UPDATE  foodcar_order  SET  status= 1  WHERE  id = '".$id."' ";
        $quer= $this->db->query($sql);
        $this->db->update('foodcar_apply_refund', array('status' => '4'), array('order_no' => $order_no));
        return $quer;
    }
    /**
     * 更新个人百度推送 userid 和 channelid
     *
     * @access public
     * @param mixed $uid
     * @param mixed $push_userid
     * @param mixed $push_channelid
     * @param mixed $device_type
     * @return void
     */
    public function upd_baidu_push($uid,$push_userid,$push_channelid,$device_type){
        $data = array(
               'baidu_push_userid' => $push_userid,
               'baidu_push_channelid' => $push_channelid,
               'device_type' => $device_type
            );
        $this->db->where('id', $uid);
        $this->db->update('biz_staff', $data); 
        //echo $this->db->last_query();
        if ($this->db->affected_rows()) {
             return true;
        }else{
            return false;
        }    
    }   

    // 
    public function get_items($ids, $fields = '*') {
        $ids = is_array($ids) ? implode(',', $ids) : $ids;
        $sql = "SELECT {$fields} FROM `biz_staff`"
            . " WHERE id IN({$ids})";
        $q = $this->db->query($sql);
        $return  = array();
        if ($q->num_rows()) {
            $result = $q->result();
            foreach ($result as & $v) {
                $return[$v->id] = $v;
            }
        }
        return $return;
    }
}