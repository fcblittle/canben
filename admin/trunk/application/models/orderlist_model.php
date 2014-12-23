<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 订单模型
 * @author 健
 * @package Order_model
 */
class Orderlist_model extends CI_Model {
    //构造
    public function __construct()
    {
        parent::__construct();
    }

    //得到菜品评论的数量
    public function  get_comments_list_total($food_id,$diner_id,$tag) {
        $set = array();
        $sql = 'select count(id) as total
                from foodcar_food_comments
                where food_id = ? and relation_id = ? and tag = ?';
        $set[] = intval($food_id);
        $set[] = $diner_id;
        $set[] = $tag;
        $query = $this->db->query($sql, $set);
        return $query->row()->total;
    }

     //得到菜品的评论列表
    public function get_comments_list($food_id,$diner_id,$tag, $rows=20, $offset=0) {
        $this->db->select('foodcar_food_comments.id,
                           foodcar_food_comments.grade,
                           foodcar_food_comments.content,
                           foodcar_food_comments.img,
                           foodcar_food_comments.img2,
                           foodcar_food_comments.img3,
                           foodcar_food_comments.voice,
                           foodcar_food_comments.soundlength,
                           foodcar_food_comments.inserttime as replydate,
                           foodcar_userinfo.nickname ');
        $this->db->from('foodcar_food_comments');
        $this->db->join('foodcar_userinfo', 'foodcar_food_comments.uid = foodcar_userinfo.id','left');
        $this->db->where('food_id', $food_id);
        $this->db->where('relation_id', $diner_id);
        $this->db->where('tag', $tag);
        $this->db->order_by('inserttime','desc');
        $this->db->limit($rows,$offset);
        $query = $this->db->get();
        //echo $this->db->last_query();  echo "<br/>";
        return $query->result_array();
    }

    //餐车管理员对账单得到全部订单列表
    public function order_list_all($diner_id,$where) {
        $sql = "SELECT
                  fo.id,fo.orderno,fo.order_amount,
                  fo.order_count,fo.insert_time,og.endtime
                FROM  foodcar_order fo
                LEFT JOIN   foodcar_order_give og  ON fo.id = og.order_id
                WHERE  fo.store_id='".$diner_id."'
                AND fo.tag=2 AND fo.status=1
                AND og.endtime is not null  AND ".$where."
                order by fo.insert_time desc ";
        $query = $this->db->query($sql);
        $data=$query->result_array();
        return $data;
    }

    //餐车管理员得到全部订单总金额
    public function get_ordermoney($diner_id,$where) {
        $sql = "SELECT sum(fo.order_amount) as amount
                FROM  foodcar_order fo
                LEFT JOIN   foodcar_order_give og  ON fo.id = og.order_id
                WHERE  fo.store_id='".$diner_id."'
                AND fo.tag=2 AND fo.status=1
                AND og.endtime is not null AND ".$where."
               ";
        $query = $this->db->query($sql);
        $data=$query->row();
        return $data;
    }

    //店小二或餐车管理员获取订单接口  配送   全部 已完成  未完成
    public function orderlist_bydiner($diner_id,$conditions){
        if( $conditions=="all"){//全部 不包含未支付和已退款的
             $where=" and fo.status<>2 and fo.status <> 4 ";
        }
        if( $conditions=="win"){//已完成
            $where=" AND fo.status= 1 ";
        }
        if( $conditions=="undone"){//未完成
            $sql_orderGive = "select order_id
                              from foodcar_order_give og
                              left join biz_staff bs
                              on og.biz_id = bs.id
                              where bs.diner_id = '".$diner_id."'";
            $query_orderGive = $this->db->query($sql_orderGive);
            $arr_orderGive = array();
            foreach ($query_orderGive->result_array() as $row) {
                $arr_orderGive[] =  $row['order_id'];
            }
            if(count(array_filter($arr_orderGive))){
                $str_orderGive = implode(",", $arr_orderGive);
            }else{
                $str_orderGive = "0";
            }
            $where=" AND (fo.status= 3 or fo.status = 5) and fo.id not in (".$str_orderGive.")";
            $this->chk_preorder($diner_id,"1");//检查 预约配送 是否有当天的订单转换为普通订单
        }
        $sql="SELECT
                fo.id,fo.orderno,fo.delivery_address,fo.expect_arrival_time,
                fo.order_amount ,fo.order_count,fo.insert_time
              FROM  foodcar_order  fo
              WHERE fo.delivery_methods='1'
              AND fo.store_id='".$diner_id."'
              AND fo.tag=2  ".$where." order by fo.insert_time desc ";
        $query = $this->db->query($sql);
        $info=$query->result_array();
        return $info;
    }

    //店小二或餐车管理员获取订单接口  自提  全部 已完成  未完成
    public function pickedup_order_bydiner($diner_id,$conditions){
        if( $conditions=="all"){//全部
             $where=" and fo.status<>2 and fo.status <> 4 ";
        }
        if( $conditions=="win"){// 已完成
            $where=" and fo.status = 1 ";
        }
        if( $conditions=="undone"){//未完成
            $where=" and (fo.status=3 or fo.status=5) ";
            $this->chk_preorder($diner_id,"2");//检查 预约配送 是否有当天的订单转换为普通订单
        }
        $sql="SELECT
                fo.id,fo.orderno,fo.delivery_address,fo.expect_arrival_time,
                fo.order_amount ,fo.order_count,fo.insert_time,fo.order_type
             FROM  foodcar_order  fo
              WHERE fo.delivery_methods='2'
              AND fo.store_id='".$diner_id."'
              AND fo.tag=2  ".$where." order by fo.insert_time desc ";
        $query = $this->db->query($sql);
        $info=$query->result_array();
        return $info;
    }

    //根据店小二id查询订单
    public function orderlist_bybizid($biz_id){
         $sql = "SELECT
                  fo.id,fo.orderno,fo.delivery_address,
                  fo.order_amount ,fo.order_count,fo.insert_time,
                  fo.expect_arrival_time
                FROM  foodcar_order fo
                LEFT JOIN  foodcar_order_give og  ON fo.id = og.order_id
                WHERE og.biz_id = '".$biz_id."' and (fo.status = 3 or fo.status = 5)
                order by fo.insert_time desc
               ";
        $query = $this->db->query($sql);
        $data=$query->result_array();
        if($data){
            return $data;
        }else{
            return "";
        }
    }
    //搜索得到订单详情
    public function  sel_order($phone,$delivery_methods,$append_where,$auth,$biz_id) {
        $sql_sel = " SELECT
                      o.id,o.orderno,o.delivery_address,
                      o.order_amount,o.order_count,o.insert_time,o.expect_arrival_time ";
        $sql_where = " WHERE  o.order_person_tel= '".$phone."' AND 
                       o.delivery_methods = '".$delivery_methods."' ".$append_where;
        if ($auth == '1') {
          $sql_from = " FROM  foodcar_order o ";
        } else {
          $sql_from = " FROM  foodcar_order o
                        LEFT JOIN  foodcar_order_give og
                        ON  o.id = og.order_id ";
          $sql_where .= " AND og.biz_id = '".$biz_id."'";
        }
        //拼写sql
        $sql = $sql_sel.$sql_from.$sql_where;
        $query = $this->db->query($sql);
        $order_info_num = $query->num_rows();
        if (!$order_info_num) {
          $sql_where2 = "WHERE u.mobile_phone = '".$phone."' AND
                        o.delivery_methods = '".$delivery_methods."' ".$append_where;
          if ($auth == '1') {
            $sql_from2 = " FROM  foodcar_order o
                          LEFT JOIN foodcar_userinfo u
                          ON o.user_id = u.id ";
          } else {
            $sql_from2 = " FROM  foodcar_order o
                          LEFT JOIN  foodcar_order_give og
                          ON  o.id = og.order_id
                          LEFT JOIN foodcar_userinfo u
                          ON o.user_id = u.id ";
            $sql_where2 .= " AND og.biz_id = '".$biz_id."'";
          }
          //拼写sql
          $sql2 = $sql_sel.$sql_from2.$sql_where2;
          $query = $this->db->query($sql2);
          $order_info =  $query->result_array();
        }else{
          $order_info =  $query->result_array();
        }
        return  $order_info;
    }
    //得到订单详情
    public function  get_order_content($order_id) {
        $sql = "select id as order_id,orderno,
                       store_name,user_id,order_person,
                       order_person_tel,delivery_address,
                       order_amount AS order_total,
                       order_count,insert_time,order_person_tel AS phone,status AS order_status,remark,expect_arrival_time
                FROM foodcar_order 
                where id =".$order_id." ";
        $query = $this->db->query($sql);
        $order_info =  $query->row_array();
        //订单号不存在
        if ( !isset ( $order_info['order_id'] ) ) {
            return array();
        }
        if (empty($order_info['remark'])) {
          $order_info['remark'] = '';
        }
        if(empty($order_info['order_person'])){
          $this->db->select('nickname,sex,mobile_phone');
          $query = $this->db->get_where('foodcar_userinfo', array('id' => $order_info['user_id']));
          $user_info =  $query->row_array();
          $order_info['order_person'] = empty($user_info['nickname'])?"匿名":$user_info['nickname'];
          $order_info['phone'] = $user_info['mobile_phone'];
          $order_info['sex'] = (empty($user_info['sex'])) ? "男" : $user_info['sex'];
        }elseif(empty($order_info['order_person_tel'])){
            $this->db->select('mobile_phone');
            $query = $this->db->get_where('foodcar_userinfo', array('id' => $order_info['user_id']));
            $user_info =  $query->row_array();
            $order_info['phone'] = $user_info['mobile_phone'];
        }else{
           $order_info['sex'] = "男";
        }
        $set2 = array();
        $sql2 = 'select food_id as dishid,
                        food_name as name,num as number,
                        unit_price as price,count as total
                 from foodcar_food_order
                 where 1 and order_id = ?';
        $set2[] = $order_id;
        $query2 = $this->db->query($sql2, $set2);
        $order_info['foods'] = $query2->result_array();
        return $order_info;
    }
    //
    public function orderlist_preorder($diner_id,$order_type,$delivery_methods){
        $sql="SELECT
                fo.id,fo.orderno,fo.delivery_address,fo.expect_arrival_time,
                fo.order_amount ,fo.order_count,fo.insert_time
              FROM  foodcar_order  fo
              WHERE
              fo.store_id='".$diner_id."' AND
              fo.delivery_methods = '".$delivery_methods."'
              AND fo.tag=2 AND order_type = '".$order_type."'  order by fo.insert_time desc ";
        $query = $this->db->query($sql);
        $orderlist=$query->result_array();
        return $orderlist;
    }

    //检查 预约配送 是否有当天的订单转换为普通订单
    private function chk_preorder($diner_id,$delivery_methods){
        $this->load->helper('date');
        $now=date('Y-m-d');
        $str=$now.' 00:00' ;
        $starttime = human_to_unix($str);
        $end=$now.' 23:00' ;
        $endtime = human_to_unix($end);
        $sql = " select id from foodcar_order
                 where delivery_methods = '".$delivery_methods."' and
                       expect_arrival_time between '".$starttime."' and '".$endtime."' ";
        $query = $this->db->query($sql);
        $order_arr = array();
        foreach ($query->result_array() as $row){
            $order_arr[] = $row['id'];
        }
        if (count(array_filter($order_arr))) {
            $order_str = implode(",", $order_arr);
        } else {
            $order_str = "0";
        }
        $upd_sql = " update foodcar_order set order_type = '1' where id in (".$order_str.") ";
        $query_upd = $this->db->query($upd_sql);
    }
}