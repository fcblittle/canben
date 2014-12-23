<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 菜品模型
 *
 * @author 健
 * @package Food_model
 */
class Food_model extends CI_Model {
    //构造
    public function __construct(){
        parent::__construct();
    }

    /*
     ** 根据关键字搜索餐车菜品列表
     */
    public function get_dinerfood($diner_id,$name){
        $sql = "SELECT od.id,od.food_name as name,od.supply_price,od.sale_price as price,od.unit,
                       od.images as img,od.description as intro,od.mealtime_id,
                       od.cate_id,od.tag_id as tag,od.aver_grade,
                       od.collection_num,od.eat_num as peoplenumber,fr.sold_out
                FROM foodcar_official_dish od
                LEFT JOIN foodcar_food_relation fr
                ON od.id = fr.food_id
                LEFT JOIN foodcar_foodclass f
                ON  f.id = od.cate_id
                WHERE fr.diner_id = '".$diner_id."' and fr.status = '1' and 
                      f.sort is not null and  od.food_name like '%$name%'
                ORDER BY od.mealtime_id asc ";
        //查询
        $query = $this->db->query($sql);       
        //得到foodlist
        $foodlist = $query->result_array();

        //载入评论模型
        $this->load->model('food_comments_model', 'reply', true);
        //添加评论
        foreach ( $foodlist as & $row ) {
            $content = $this->reply->get_last_comment($row['id'],$diner_id,"2");
            $arr_num = count(array_filter($content));
            $row['content'] = ($arr_num) ? $content['content'] : "";
            $images = explode(",", $row['img']);
            foreach ($images as $key => $item) {
                $images[$key] =  base_url().stripslashes($item);
            }
            $row['img'] = $images[0];//$row['img'] = implode(",", $images);
        }
        //返回
        return $foodlist;
    }

    /**
     * 得到菜品列表
     *
     * @access public
     * @param int $rows
     * @param int $offset
     * @param mixed $kw
     * @return void
     */
    public function get_food_list($rows=20, $offset=0, $kw="") {
        //设置
        $set = array();
        //拼接语句
        $sql = 'select foodinfo.id as food_id,
                       foodinfo.store_id,
                       foodinfo.food_name,
                       foodinfo.price,
                       foodinfo.unit,
                       foodinfo.description,
                       foodinfo.eat_num,
                       foodinfo.images,
                       store.store_name
                from foodcar_food_info as foodinfo,
                     foodcar_store as store
                where  foodinfo.store_id = store.id';
        if (isset($kw)) {
            $sql .= ' and food_name like ?';
            $set[] = "%$kw%";
        }
        $sql .= ' order by foodinfo.eat_num desc limit ?,?';
        $set[] = $offset;
        $set[] = $rows;
        //查询
        $query = $this->db->query($sql, $set);
        //返回
        return $query->result_array();
    }
    /**
     * 得到菜品总数
     *
     * @access public
     * @param mixed $kw
     * @return void
     */
    public function get_food_total($kw=null) {
        //设置
        $set = array();
        //拼接语句
        $sql = 'select count(id) as total from foodcar_food_info where 1';
        if (isset($kw)) {
            $sql .= ' and food_name like ?';
            $set[] = "%$kw%";
        }
        //查询
        $query = $this->db->query($sql, $set);
        //结果
        $res = $query->row_array();
        //返回
        return isset ($res['total']) ? $res['total'] : 0;
    }
    /**
     * 得到一个商铺推荐的菜品
     * 根据吃的人数排序，默认取20条
     *
     * @access public
     * @param int $id uid rows
     * @return void
     */
    public function get_food_toplist($merchant_id=0,$rows=20) {
        //设置
        $sql = "SELECT fi.id,
                       fi.food_name as name,
                       fi.price,fi.unit,
                       fi.images as img,
                       fi.description as intro,
                       fi.merchant_id,fi.aver_grade,
                       fi.cate_id ,
                       fi.eat_num as peoplenumber
                FROM foodcar_food_info fi 
                LEFT JOIN foodcar_dish_category dc
                ON fi.cate_id = dc.id AND fi.merchant_id = dc.merchant_id
                WHERE fi.merchant_id = '".$merchant_id."'";
        $sql .= " ORDER BY fi.eat_num desc limit ".$rows."";
        //,
        //               fr.relation_type as tag,fr.relation_id,
        //               fr.sold_out
        //查询
        $query = $this->db->query($sql);
        //得到toplist
        $toplist = $query->result_array();
        /*$sql2 = " SELECT fo.food_id
                  FROM foodcar_order o
                  LEFT JOIN foodcar_food_order fo
                  ON o.id = fo.order_id
                  WHERE o.user_id = '".$uid."' ";
        //查询
        $query2 = $this->db->query($sql2);
        $order_foodid = $query2->result_array();
        $foods = array();
        //将二维数组转为一维数组
        foreach ($order_foodid as $value) {
            $foods[] = $value['food_id'];
        }*/
        //载入评论模型
        $this->load->model('food_comments_model', 'reply', true);
        //添加评论
        foreach ( $toplist as & $row ) {
            /*if (in_array($row['id'], $foods)) {
                $row['comments_isexist'] = 1;
            }else{
                $row['comments_isexist'] = 0;
            }*/
            $content = $this->reply->get_last_comment($row['id'],$merchant_id,"1");
            $arr_num = count(array_filter($content));
            $row['content'] = ($arr_num) ? $content['content'] : "";
            if(empty($row['img'])){
                $row['img'] = "";
            }else{
                $row['img'] = MERCHANT_URL.stripslashes($row['img']);
            }
        }
        //返回
        return $toplist;
    }
    /**
     * 得到餐厅全部菜品
     *
     * @access public
     * @param mixed $store_id
     * @return void
     */
    public function get_foodlist($merchant_id=0) {
        //设置sql语句
        $sql = "SELECT 
                    fi.id, fi.food_name as name, fi.price,fi.unit, fi.images as img, 
                    fi.description as intro, fi.merchant_id,fi.aver_grade, 
                    fi.cate_id , fi.eat_num as peoplenumber ,dc.weight as sort
                FROM foodcar_food_info fi
                LEFT JOIN foodcar_dish_category dc
                ON fi.cate_id = dc.id  AND  fi.merchant_id = dc.merchant_id
                WHERE fi.merchant_id = '".$merchant_id."'
                ORDER BY sort asc";
// and dc.weight is not null              
        //,
        //               fr.relation_type as tag,fr.relation_id,
        //               fr.sold_out LEFT JOIN foodcar_food_relation  fr ON fi.id = fr.food_id
        //查询
        $query = $this->db->query($sql);
        //得到foodlist
        $foodlist = $query->result_array();
        /*$sql3 = " SELECT fo.food_id
                  FROM foodcar_order o
                  LEFT JOIN foodcar_food_order fo
                  ON o.id = fo.order_id
                  WHERE o.user_id = '".$uid."' ";
        //查询
        $query3 = $this->db->query($sql3);
        $order_foodid = $query3->result_array();
        $foods = array();
        foreach ($order_foodid as $value) {
            $foods[] = $value['food_id'];
        }*/
        //载入评论模型
        $this->load->model('food_comments_model', 'reply', true);
        //添加评论
        foreach ( $foodlist as & $row ) {
            /*if (in_array($row['id'], $foods)) {
                $row['comments_isexist'] = 1;
            }else{
                $row['comments_isexist'] = 0;
            }*/
            $content = $this->reply->get_last_comment($row['id'],$merchant_id,"1");
            $arr_num = count(array_filter($content));
            $row['content'] = ($arr_num) ? $content['content'] : "";
            if(empty($row['img'])){
                $row['img'] = "";
            }else{
                $row['img'] = MERCHANT_URL.stripslashes($row['img']);
            }
        }
        //返回
        return $foodlist;
    }

    /**
     * 根据菜品id得到菜品详情
     *
     * @access public
     * @param mixed $food_id
     * @return void
     */
    public function get_fooddish_by_id($food_id,$merchant_id,$yorn=true) {
        //设置
        $set = array();
        //设置sql语句
        $sql = "SELECT fi.id,
                       fi.food_name as name,price,unit,
                       fi.images as img,
                       fi.description as intro,
                       fi.aver_grade,
                       fi.eat_num as peoplenumber
                FROM foodcar_food_info fi
                where fi.id = '".$food_id."' and fi.merchant_id = '".$merchant_id."' ";
                // LEFT JOIN foodcar_food_relation  fr
                //ON fi.id = fr.food_id
        //查询
        $query = $this->db->query($sql);
        //得到food
        $food = $query->row_array();
        if(count($food)){
            $food['img'] = MERCHANT_URL.stripslashes($food['img']);
            /*if($yorn){
                //载入评论模型
                $this->load->model('food_comments_model', 'reply', true);
                $return_res = $this->reply->get_last_comment($food['id']);
                $num_sel = count(array_filter($return_res));
                if($num_sel){
                    if(empty($return_res['voice'])){
                        $return_res['voice'] = "";
                    }else{
                        $return_res['voice'] = base_url().substr(stripslashes($return_res['voice']), 1);
                    }
                    $food['replylist'] = $return_res;
                }else{
                    $food['replylist'] = array();
                }
            }else{
                $table = ($tag = "1") ? "foodcar_store" : "foodcar_diner";
                $this->db->select('delivery_yorn');
                $query = $this->db->get_where($table, array('id' => $id));
                $row = $query->row();
                $food['delivery_yorn'] = $row->delivery_yorn;
            }*/
            //返回
            return $food;
        }else{
            return array();
        }
    }
    /**
     * 根据菜品id得到菜品详情
     *
     * @access public
     * @param mixed $food_id
     * @return void
     */
     public function get_dinerdish_byid($food_id,$diner_id){
        $this->db->select('id, food_name as name , sale_price as price,
                           unit,images as img,intro,description,mealtime_id,
                           aver_grade,collection_num ,eat_num as peoplenumber ');
        $query = $this->db->get_where('foodcar_official_dish', array('id' => $food_id));
        $food = $query->row_array();

        //获取 餐车是否送餐
        $sql = " select delivery_yorn from foodcar_diner where id = ".$diner_id;
        $query = $this->db->query($sql);
        $food['delivery_yorn'] = $query->row()->delivery_yorn;
        //载入模型
        $this->load->model('food_comments_model', 'comments', true);
        $food_total = $this->comments->get_comments_list_total($food_id,$diner_id,2); 
        if(count($food)){
            $images = explode(",", $food['img']);
            foreach ($images as $key => $item) {
                $images[$key] =  base_url().stripslashes($item);
            }
            $food['img'] = implode(",", $images);
            $food['dish_url'] = base_url().stripslashes("index.php/admini/food/dish/".$food_id."/".$diner_id);
            $food['gradeNum'] = $food_total;
            return $food;
        }else{
            return array();
        }
     }
    /**
     * 根据food_id得到food图片地址
     *
     * @access public
     * @param mixed $food_id
     * @return void
     */
    public function get_img_by_id($food_id) {
        //设置
        $set = array();
        //设置sql语句
        $sql = 'select images as img from foodcar_official_dish where id = ?';
        $set[] = $food_id;
        //查询
        $query = $this->db->query($sql, $set);
        return $query->row()->img;
    }
    /**
     * 更新eat_num数量
     *
     * @access public
     * @param mixed $food_id
     * @return void
     */
    public function update_eat_num($food_id) {
        //设置
        $set = array();
        $sql = 'update foodcar_food_info set eat_num = eat_num + 1 where id = ?';
        $set[] = intval($food_id);
        //执行
        $this->db->query($sql, $set);
        return true;
    }
    //菜品查询
    public function  get_all_foodinfo(){
        $sql="select id,food_name,price,unit,images from foodcar_food_info order by id desc";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        $res = $query->result_array();
        if($rownum){
            return $res;
        }else{
            return "";
        }
    }
    //
    public function get_dinerfood_toplist($diner_id,$rows=20){
        $sql = "SELECT od.id,od.food_name as name,od.sale_price as price,od.unit,
                       od.images as img,od.description as intro,od.mealtime_id,
                       od.cate_id,od.tag_id as tag,od.aver_grade,
                       od.collection_num,od.eat_num as peoplenumber,fr.sold_out
                FROM foodcar_official_dish od
                LEFT JOIN foodcar_food_relation fr
                ON od.id = fr.food_id
                WHERE fr.diner_id = '".$diner_id."' and fr.status = '1'
                ORDER BY od.eat_num desc limit ".$rows."";
        //查询
        $query = $this->db->query($sql);
        //得到toplist
        $toplist = $query->result_array();
        //载入评论模型
        $this->load->model('food_comments_model', 'reply', true);
        //添加评论
        foreach ( $toplist as & $row ) {
            /*if (in_array($row['id'], $foods)) {
                $row['comments_isexist'] = 1;
            }else{
                $row['comments_isexist'] = 0;
            }*/
            $content = $this->reply->get_last_comment($row['id'],$diner_id,"2");
            $arr_num = count(array_filter($content));
            $row['content'] = ($arr_num) ? $content['content'] : "";
            $row['img'] = base_url().stripslashes($row['img']);
        }
        //返回
        return $toplist;

    }
    //
    public function get_dinerfoodlist($diner_id){
        //搜出时间段
        /*$sql_mealtime = " SELECT 
                            trip_time1_start,trip_time1_end,
                            trip_time2_start,trip_time2_end,
                            trip_time3_start,trip_time3_end
                          FROM foodcar_diner                            
                          WHERE id = '".$diner_id."'
                        ";                      
        $query_mealtime = $this->db->query($sql_mealtime);
        $row_mealtime = $query_mealtime->row();
        $mealtime_id = 0;
        date_default_timezone_set("Etc/GMT-8");
        $time = date('H:i',time());
        if (!empty($row_mealtime->trip_time1_end) && 
            strtotime($row_mealtime->trip_time1_end) > strtotime($time)) {
                $mealtime_id = "1";
        }elseif (!empty($row_mealtime->trip_time2_end) && 
                 strtotime($row_mealtime->trip_time2_end) > strtotime($time)) {
                $mealtime_id = "2";
        }else{
            $mealtime_id = "3";
        }*/
        //and od.mealtime_id = '".$mealtime_id."'
        $sql = "SELECT od.id,od.food_name as name,od.supply_price,od.sale_price as price,od.unit,
                       od.images as img,od.description as intro,
                       od.cate_id,od.tag_id as tag,od.aver_grade,
                       od.collection_num,od.eat_num as peoplenumber,fr.sold_out
                FROM foodcar_official_dish od
                LEFT JOIN foodcar_food_relation fr
                ON od.id = fr.food_id
                LEFT JOIN foodcar_foodclass f
                ON  f.id = od.cate_id
                WHERE fr.diner_id = '".$diner_id."'  and fr.status = '1' and 
                      f.sort is not null 
                ORDER BY od.id asc ";
        //查询
        $query = $this->db->query($sql);       
        //得到foodlist
        $foodlist = $query->result_array();
        //载入评论模型
        $this->load->model('food_comments_model', 'reply', true);
        //添加评论
        foreach ( $foodlist as & $row ) {
            $content = $this->reply->get_last_comment($row['id'],$diner_id,2);
            $arr_num = count(array_filter($content));
            $row['content'] = ($arr_num) ? $content['content'] : "";
            $images =  isset($row['img']) ? $row['img'] : array();
            $images = explode(",", $row['img']);
            foreach ($images as $key => $item) {
                $images[$key] =  base_url().stripslashes($item);
            }
            $row['img'] = $images[0];//implode(",", $images);
        }
        //返回
        return $foodlist;
    }
}