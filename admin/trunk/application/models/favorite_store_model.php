<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 用户餐厅、餐车收藏夹
 *
 * @author zaichen
 * @package Favorite_store_model
 */
 class Favorite_store_model extends CI_Model {
     //构造
    public function __construct(){
        parent::__construct();
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
                from foodcar_collection_store
                where user_id = ?';
        $set[] = $uid;
        //查询
        $query = $this->db->query($sql, $set);
        //返回总数
        return $query->row()->total;
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
        $sql = 'select store_id
                from foodcar_collection_store
                where user_id = ? order by pubdate desc limit ?,?';
        $set[] = $uid;
        $set[] = $offset;
        $set[] = $num;
        $query = $this->db->query($sql, $set);
        $result = $query->result_array();
        $favorite_store = array();
        $res_count = count(array_filter($result)); 
        if (!$res_count) {
            return array();
        }else{
            //载入模型
            $this->load->model('store_model', 'store', true);
            $filedstr = " id,merchant_id,store_name as name ,store_logo as img,store_tel as tel,address ";
            foreach($result as $row){
                $favorite_store_arr = $this->store->getinfo_by_storeid($row['store_id'], $filedstr); 
                if(count($favorite_store_arr)){
                    $favorite_store_arr['img']  = base_url().substr(stripslashes($favorite_store_arr['img']), 1);
                    $favorite_store[] = $favorite_store_arr ;
                }else{
                    continue;
                }                
            }
            return $favorite_store;            
        }
    }
    /**
     * 添加餐厅收藏
     *
     * @access public
     * @return void
     */
    public function add_favorite($uid, $store_id) {
        //检查菜品是否已在用户收藏夹中
        if ( $this->check_store_favoreite( $uid, $store_id ) == true ) {
            return true;
        }
        //设置
        $set = array();
        //拼接语句
        $sql = 'insert into foodcar_collection_store ( user_id, store_id, pubdate ) values (?,?,?)';
        $set[] = $uid;
        $set[] = $store_id;
        $set[] = time();
        $this->db->query($sql, $set);
        return true;
    }
    /**
     * 检查用户收藏夹里的商铺是否已存在
     *
     * @access public
     * @return void
     */
    public function check_store_favoreite($uid, $store_id) {
        //设置
        $set = array();
        //语句
        $sql = 'select id
                from foodcar_collection_store
                where user_id=? and store_id=?';
        $set[] = $uid;
        $set[] = $store_id;
        $query = $this->db->query($sql, $set);
        if ( isset($query->row()->id) ) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 检查用户收藏夹里的餐车是否已存在
     *
     * @access public
     * @return void
     */
    public function check_diner_favoreite($uid, $diner_id) {
        //设置
        $set = array();
        //语句
        $sql = 'select id
                from foodcar_collection_diner
                where user_id=? and diner_id=?';
        $set[] = $uid;
        $set[] = $diner_id;
        $query = $this->db->query($sql, $set);
        if ( isset($query->row()->id) ) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 删除收藏
     *
     * @access public
     * @param mixed $uid $store_id
     * @return void
     */
    public function del_favorite($uid, $store_id) {
        //设置
        $set = array();
        //语句
        $sql = 'delete from foodcar_collection_store where user_id = ? and store_id = ?';
        $set[] = $uid;
        $set[] = $store_id;
        $this->db->query($sql, $set);
        return true;
    }

 }