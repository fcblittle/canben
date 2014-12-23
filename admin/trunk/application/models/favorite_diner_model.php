<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 用户餐车收藏夹
 *
 * @author zaichen
 * @package Favorite_diner_model
 */
 class Favorite_diner_model extends CI_Model {
    /**
     *构造
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * 得到用户收藏夹收藏餐车总数
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
                from foodcar_collection_diner
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
        $sql = 'select diner_id
                from foodcar_collection_diner
                where user_id = ? order by pubdate desc limit ?,?';
        $set[] = $uid;
        $set[] = $offset;
        $set[] = $num;
        $query = $this->db->query($sql, $set);
        $result = $query->result_array();
        $favorite_diner = array();
        $res_count = count(array_filter($result));
        if (!$res_count) {
            return array();
        }else{
            //载入模型
            $this->load->model('diner_model', 'diner', true);
            $filedstr = " id,merchant_id,diner_name,car_license_plate,
                          first_person_tel,images,collection_num,address ";
            foreach($result as $row){
                $favorite_diner_arr = $this->diner->getinfo_by_dinerId($row['diner_id'], $filedstr);
                $favorite_diner_arr['images'] = ($favorite_diner_arr['images']) ?
                         base_url().stripslashes( $favorite_diner_arr['images']):
                         base_url().stripslashes('asset/images/diner/diner_icon.png');
                if(count($favorite_diner_arr)){
                    $favorite_diner[] = $favorite_diner_arr ;
                }else{
                    continue;
                }
            }
            return $favorite_diner;
        }
    }
    /**
     * 添加餐车收藏
     *
     * @access public
     * @return void
     */
    public function add_favorite($uid, $diner_id) {
        //检查菜品是否已在用户收藏夹中
        if ( $this->check_diner_favoreite( $uid, $diner_id ) == true ) {
            return 0;
        }
        $sql_upd = "update foodcar_diner set collection_num = collection_num + 1
                    where id = '".$diner_id."' ";
        $query_upd = $this->db->query($sql_upd);
        if ($query_upd) {
            //设置
            $set = array();
            //拼接语句
            $sql = 'insert into foodcar_collection_diner ( user_id, diner_id, pubdate ) values (?,?,?)';
            $set[] = $uid;
            $set[] = $diner_id;
            $set[] = time();
            return $this->db->query($sql, $set);
        } else {
            return 0;
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
     * @param mixed $uid $diner_id
     * @return void
     */
    public function del_favorite($uid, $diner_id) {
        //设置
        $set = array();
        //语句
        $sql = 'delete from foodcar_collection_diner where user_id = ? and diner_id = ?';
        $set[] = $uid;
        $set[] = $diner_id;
        return $this->db->query($sql, $set);
    }

 }