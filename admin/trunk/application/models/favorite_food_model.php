<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 用户菜品收藏夹
 *
 * @author 健
 * @package Favorite_food_model
 */
class Favorite_food_model extends CI_Model {
    //构造
    public function __construct(){
        parent::__construct();
    }
    /**
     * 得到收藏列表
     *
     * @access public
     * @param mixed $uid
     * @param int $num
     * @param int $offset
     * @return void
     */
    public function get_favorite_list($uid, $num=20, $offset=0) {
        //设置
        $set = array();
        //语句
        $sql = 'SELECT cf.*,dish.supply_price
                FROM `foodcar_collection_food` AS cf
                LEFT JOIN `foodcar_food_relation` AS fr
                ON cf.food_id = fr.food_id and cf.relation_id = fr.diner_id
                LEFT JOIN `foodcar_official_dish` AS dish
                ON cf.food_id = dish.id
                WHERE cf.user_id = ? and cf.tag = 2 order by pubdate desc limit ?,?';
        $set[] = $uid;
        $set[] = $offset;
        $set[] = $num;
        $query = $this->db->query($sql, $set);
        $result = $query->result_array();
        //定义结果
        /*$favorite = array();
        foreach ( $result as $row ) {
            $favorite[] = $row['food_id'];
        }
        //检查是否有收藏
        if ( count ( $favorite ) < 1 ) {
            return array();
        }*/
        if(count($result)){
            //载入模型
            $this->load->model('food_model', 'food', true);
            foreach ( $result as $row) {
                //$food_id,$id,$tag,$yorn=true
                if($row['tag'] == '1'){
                    $food_arr = $this->food->get_fooddish_by_id($row['food_id'],$row['relation_id'],false);
                    $num = count(array_filter($food_arr));
                    if($num){$res[] = $food_arr;}                     
                }else{
                    $food_arr = $this->food->get_dinerdish_byid($row['food_id'],$row['relation_id']);
                    $num = count(array_filter($food_arr));
                    if($num){
                        $food_arr['diner_id'] = $row['relation_id'];
                        $food_arr['supply_price'] = $row['supply_price'];
                        $this->db->select('diner_name,delivery_yorn');
                        $query_name = $this->db->get_where('foodcar_diner', array('id' => $row['relation_id']));
                        $row_name = $query_name->row();  
                        $food_arr['diner_name'] = $row_name->diner_name;
                        $food_arr['delivery_yorn'] = $row_name->delivery_yorn;
            			$food_arr['collect_yorn'] = '1';
                        $res[] = $food_arr;
                    }   
                }
            }
            return $res;
        }else{
            return array();
        }
    }
    /**
     * 得到用户收藏夹收藏总数
     *
     * @access public
     * @param mixed $uid
     * @return void
     */
    public function get_favorite_total($uid) {
        //设置
        $set = array();
        //拼接语句
        $sql = 'select count(id) as total
                from foodcar_collection_food
                where user_id = ?';
        $set[] = $uid;
        //查询
        $query = $this->db->query($sql, $set);
        //返回总数
        return $query->row()->total;
    }
    /**
     * 添加收藏
     *
     * @access public
     * @return void
     */
    public function add_favorite($uid,$food_id,$id,$tag) {
        //检查菜品是否已在用户收藏夹中
        if ( $this->check_food_favoreite($uid,$food_id,$id,$tag) === true ) {
            return false;
        }else{
            //设置
            $set = array();
            $set['food_id'] = $food_id;
            $set['user_id'] = $uid;
            $set['relation_id'] = $id;
            $set['tag'] = $tag;
            $set['pubdate'] = time();
            $this->db->insert('foodcar_collection_food', $set);
            return true;
        }
    }
    /**
     * 删除收藏
     *
     * @access public
     * @param mixed $uid,$food_id,$id,$tag
     * @return void
     */
    public function del_favorite($uid,$food_id,$id,$tag) {
        //设置
        $where = array();
        $where['food_id'] = $food_id;
        $where['user_id'] = $uid;
        $where['relation_id'] = $id;
        $where['tag'] = $tag;
        $this->db->delete("foodcar_collection_food",$where);
        return true;
    }
    /**
     * 检查用户收藏夹里的菜品是否已存在
     *
     * @access public
     * @return void
     */
    public function check_food_favoreite($uid,$food_id,$relation_id,$tag) {
        //设置
        $set = array();
        //语句
        $sql = 'select id
                from foodcar_collection_food
                where user_id=? and food_id=? and relation_id = ? and tag = ? ';
        $set[] = $uid;
        $set[] = $food_id;
        $set[] = $relation_id;
        $set[] = $tag;
        $query = $this->db->query($sql, $set);
        if ( isset($query->row()->id) ) {
            return true;
        } else {
            return false;
        }
    }
}