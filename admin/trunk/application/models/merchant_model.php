<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 商户模型
 *
 * @author liuyunxia
 * @author 健
 * @package Store_model
 */
class Merchant_model extends CI_Model {
//常量 盐值
    const salt = 'wooxqwla;fovgjvbwoswiq';
    //构造
    public function __construct() {
        parent::__construct();
    }
    /*** 商户得到pwd * @author liuyunxia */
    private function password($pwd) {
        return $this->encrypt->encode($pwd);
    }
    //得到 餐车内的全部菜品 
    public function  get_diner_foodlist($diner_id) {
        $diner_id= intval($diner_id);
        $sql = "SELECT od.id,od.revision_id,od.food_name as name,
                       od.sale_price as price,od.unit,
                       od.images as img,od.description,
                       od.cate_id,od.tag_id,fr.sold_out
                FROM foodcar_official_dish AS od
                LEFT JOIN foodcar_food_relation AS fr
                ON od.id = fr.food_id
                WHERE fr.diner_id = ? and fr.status=1
                ORDER BY od.mealtime_id desc ";
        $query = $this->db->query($sql, array($diner_id));
        $list = $query->result_array();
        return $list;
    }
    //时间段内 餐车内的全部菜品 
    public function  get_mealtime_foodlist($diner_id,$mealtime_id = false) {
        $diner_id= intval($diner_id);
        $mealtime_id= ! empty($mealtime_id) ? intval($mealtime_id) : false;
        $sql = "SELECT
                   fi.id,fi.food_name as name,
                   fi.sale_price as price,fi.images as img,fr.sold_out
                FROM foodcar_official_dish fi
                LEFT JOIN foodcar_food_relation fr  ON  fr.food_id=fi.id
                WHERE  fr.diner_id =$diner_id AND fr.status = 1";
        if (! empty($mealtime_id)) {
            $sql .= " AND fi.mealtime_id = $mealtime_id";
        }
        $sql .= " ORDER BY  fi.id ASC";
        $query = $this->db->query($sql);
        $list = $query->result_array();
        $str_column = "";
        if ($mealtime_id=='1') {
            $str_column = " trip_time1_start as start , trip_time1_end as end ";
        } elseif ($mealtime_id=='2') {
            $str_column = " trip_time2_start as start , trip_time2_end as end ";
        } else {
            $str_column = " trip_time3_start as start , trip_time3_end as end ";
        }
        $sql_trip = "select ".$str_column." from foodcar_diner where id = '".$diner_id."'";
        $query_trip = $this->db->query($sql_trip);
        $res_trip = $query_trip->result_array();
        date_default_timezone_set("Etc/GMT-8");
        if (!empty($res_trip['start']) && !empty($res_trip['end'])) {
            $time = date('H:i',time());
            if (strtotime($res_trip['start']) < strtotime($time) && strtotime($res_trip['end']) > strtotime($time)) {
                $status = 1;//正常售卖
            }else{
                $status = 0;//暂时不到售卖时间
            }
        }else{
            $status = 0;
        }
        for ($i=0; $i < count($list); $i++) { 
            $list[$i]['status'] = $status;
        }
        return $list;
    }
    //餐车管理员添加菜品状态
    public function  sold_out($food_id,$diner_id,$mealtime_id) {
        /*$sql_sel = " select od.id 
                     from foodcar_official_dish od 
                     left join foodcar_food_relation fr 
                     on od.id = fr.food_id
                     where 
                     od.mealtime_id = '".$mealtime_id."' and 
                     fr.diner_id = '".$diner_id."' and
                     fr.sold_out = 1 
                   ";
        $query_sel = $this->db->query($sql_sel);
        $row_sel = $query_sel->result_array();
        $dish_arr = array();
        foreach ($row_sel as $value) {
            $dish_arr[] = $value['id'];
        }
        $count_dish_arr = count(array_filter($dish_arr));
        if ($count_dish_arr) {
            $dish_str = implode(",", $dish_arr);
            $sql_sold = " update foodcar_food_relation set sold_out = 0 where food_id in (".$dish_str.") ";
            $query_sold = $this->db->query($sql_sold);
        }*/
        //
        $food_arr = array();
        foreach ($food_id as $value) {
            $food_arr[] = $value['id'] ;
        }
        $food_str = implode(",", $food_arr);
        $sql_reset = " update foodcar_food_relation set sold_out = 1 where diner_id = '".$diner_id."' and food_id in (".$food_str.") ";
        $query_reset = $this->db->query($sql_reset);
        $affect_num = $this->db->affected_rows();
        return $affect_num;
       /*if($query){
           $add=array();
           $cid=count($food_id);
           for($i=0;$i<$cid;$i++) 
           {   
                $where['food_id']=$food_id[$i]['id'];
                $where['diner_id']=$diner_id;
                              $foodstauts = array(
                                "sold_out"=>1,
                                );
                $this->db->update('foodcar_food_relation',$foodstauts,$where); 
                $add[$i]=$where['food_id'];
           }
          return $add;
       }else{
          return  $data;
       }*/  
    }
    //得到商户菜品分类的全部菜品
    public function  get_type_foodlist($diner_id) {
        $sql1 = "SELECT od.id,od.food_name,od.sale_price AS price,
                od.unit,od.images AS img,od.aver_grade,od.collection_num,od.eat_num,
                fr.diner_id,fr.merchant_id,fr.status,fr.sold_out
                FROM foodcar_official_dish  od
                LEFT JOIN foodcar_food_relation fr 
                ON od.id = fr.food_id
                WHERE fr.diner_id = $diner_id";
        $query1 = $this->db->query($sql1);
        $list = $query1->result_array(); //var_dump($list);echo "<br/>";
        //echo $this->db->last_query();echo "<br/>";
        $num = count($list);
        for ($i=0; $i <$num ; $i++) {
            $this->db->select('id'); 
            $query = $this->db->get_where('foodcar_food_comments', array('food_id' => $list[$i]['id'],'relation_id' =>$list[$i]['diner_id'],'tag' =>'2' ));
            $comment_num = $query->num_rows();
            $list[$i]['comment_num'] = $comment_num;
        }
        return $list;
    }
    // 促销管理列表
    public function get_saleslist($merchant_id,$state) {
        if($state=="not"){
            $where=" and start>".time()." ";
        }
        if($state=="go"){
            $where=" and  end>".time()." and start<".time()." ";
        }
        if($state=="over"){
            $where=" and end <".time()." ";
        }
        $sql = " SELECT 
                   id,title ,type,discount,start,end
                FROM 
                    biz_promotion
               WHERE 1  ".$where." AND merchant_id = ".$merchant_id." ";
        $query = $this->db->query($sql);
        $list = $query->result_array();
        return $list;
    }
    //促销管理列表详情
    public function get_sales($pro_id) {
        $sql = " select dish,discount from biz_promotion where id=".$pro_id."";
        $query = $this->db->query($sql);
        $list1 = $query->row();
        $num=$query->num_rows();
        if($num){
            $dish=explode(",",$list1->dish);
            $new=$list1->discount/100;
            $count=count($dish);
            for($i=0;$i<$count;$i++){
                $id=$dish[$i];
                $sq = " select 
                               foodcar_official_dish.food_name,
                               foodcar_official_dish.sale_price as price,foodcar_official_dish.sale_price*".$new." as new,
                               foodcar_official_dish.images,
                               foodcar_foodlabel.flabel_name 
                        from foodcar_official_dish 
                        left join foodcar_foodlabel 
                        on foodcar_foodlabel.id=foodcar_official_dish.cate_id
                        where foodcar_official_dish.id=".$id."";
                $quer = $this->db->query($sq);
                $list[$i]= $quer->row_array();
            }
            return $list;
        }else{
            return "";
        }
    }

    public function get_items($ids, $fields = '*') {
        $ids = is_array($ids) ? implode(',', $ids) : $ids;
        $sql = "SELECT {$fields} FROM `foodcar_merchant`"
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

    public function getMerchantList()
    {
        $result = $this->db->select('id, apply_name')->where('status', 1)->get('`foodcar_merchant`')->result();
        $data = array();
        foreach ($result as $item) {
            $data[$item->id] = $item->apply_name;
        }

        return $data;
    }

    public function getManagerList()
    {
        $result = $this->db->select('id, realname')->where('status', 1)->get('`biz_staff`')->result();
        $data = array();
        foreach ($result as $item) {
            $data[$item->id] = $item->realname;
        }

        return $data;
    }

    public function get_withdrawal_list($args = array())
    {
        $tableWithdrawal = '`foodcar_merchant_withdrawals`';

        $conds = $limitStr = '';
        $binds = array();

        $params = array_merge(array(
            'fields'  => '*',
            'orderby' => 'submit_time DESC, status ASC',
            'status'  => null,
            'userType'=> null,
            'mobile'  => null,
            'dateInterval' => array()
        ), $args);

        // 搜索条件
        if (! empty($params['status'])) {
            $conds .= " AND status = ?";
            $binds[] = $params['status'];
        }
        if (! empty($params['userType'])) {
            $conds .= " AND userType = ?";
            $binds[] = $params['userType'];
        }
        if (! empty($params['mobile'])) {
            $conds .= " AND mobile LIKE ?";
            $binds[] = "%" . $params['mobile'] . "%";
        }
        if (! empty($params['dateInterval'])) {
            $conds .= " AND submit_time BETWEEN ? AND ?";
            $binds[] = $params['dateInterval']['beginnig'];
            $binds[] = $params['dateInterval']['end'];
        }

        // 分页字符串拼接
        $limitStr = " LIMIT " . ($params['pager']['page'] )* $params['pager']['limit'] . ", " . $params['pager']['limit'];

        $sql = "SELECT {$params['fields']}
                FROM {$tableWithdrawal}
                WHERE 1 $conds";

        $query = $this->db->query($sql . $limitStr, $binds);
        $cQuery = $this->db->query($sql, $binds);

        return array(
            'total' => $cQuery->num_rows(),
            'list'  => $query->result(),
        );
    }
}?>