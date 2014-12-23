<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 餐厅/餐车评论
 *
 * @author sj
 * @package Store_comments_model
 */
class Store_comments_model extends CI_Model {
    //构造
    public function __construct(){
        parent::__construct();
    }
    /**
     * 得到最新的3条评论
     *
     * @access public
     * @param mixed $food_id
     * @return void
     */
    public function get_last_three_comment($food_id) {
        return $this->get_comments_list($store_id, 3, 0);
    }
    /**
     * 得到最新的评论
     *
     * @access public
     * @param mixed $food_id
     * @return void
     */
    public function get_last_comment($store_id) {
        return $this->get_comments_list($store_id, 1, 0);
    }
    /**
     * 得到餐厅的评论列表
     *
     * @access public
     * @param mixed $store_id
     * @param int $rows
     * @param int $offset
     * @return void
     */
    public function get_comments_list($store_id, $rows=20, $offset=0) {
        //设置
        $set = array();
        //拼接语句
        $sql = 'select comments.id as replyid,comments.content,comments.taste,
                       comments.atmosphere,comments.service,comments.per_capita,
                       comments.inserttime as replydate,userinfo.nickname,
                       userinfo.head_portrait
                from foodcar_store_comments as comments,foodcar_userinfo as userinfo
                where 1 and comments.store_id = ? and comments.uid = userinfo.id
                order by inserttime desc limit ?,?';
        $set[] = intval($store_id);
        $set[] = $offset;
        $set[] = $rows;
        //查询
        $query = $this->db->query($sql, $set);
        //返回
        return $query->result_array();
    }
    /**
     * 得到餐厅评论的数量
     *
     * @access public
     * @param mixed $food_id
     * @return void
     */
    public function get_comments_list_total($store_id) {
        //设置
        $set = array();
        //拼接语句
        $sql = 'select count(id) as total
                from foodcar_store_comments
                where 1 and store_id = ?';
        $set[] = intval($store_id);
        //查询
        $query = $this->db->query($sql, $set);
        //返回总数
        return $query->row()->total;
    }
    /**
     * 添加评论
     *
     * @access public
     * @param mixed $store_id
     * @param mixed $uid
     * @param int $grade
     * @param string $taste
     *               $atmosphere
     *               $service
     *               $content
     *               $per_capita
     * @return void
     */
    public function add_reply( $uid,$store_id,$taste,
                               $atmosphere, $service, $grade,
                               $per_capita, $content='') {
        //设置
        $set = array();
        //拼接语句
        $sql = 'insert into foodcar_store_comments (store_id, uid, grade, taste, atmosphere,
                                                    service, content, per_capita, inserttime)
                values (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $set[] = $store_id;
        $set[] = $uid;
        $set[] = intval($grade);
        $set[] = $taste;
        $set[] = $atmosphere;
        $set[] = $service;
        $set[] = $content;
        $set[] = $per_capita;
        $set[] = time();
        $this->db->query($sql, $set);
        $query = $this->db->query("Update foodcar_store set comment_num = comment_num + 1 where id = '".$store_id."'");
        //echo "Update foodcar_store set comment_num = comment_num + 1 where id = '".$store_id."'"; exit;
        return $this->db->insert_id();
    }
}