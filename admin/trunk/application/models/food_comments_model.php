<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 菜品评论模型
 *
 * @author 健
 * @package Store_model
 */
class Food_comments_model extends CI_Model {
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
    public function get_last_comment($food_id,$relation_id,$tag) {
        //设置
        $set = array();
        //拼接语句
        $sql = 'select id as replyid,nickname,content,
                       grade,voice,soundlength,inserttime as replydate
                from foodcar_food_comments
                where food_id = ? and relation_id = ? and tag = ?
                order by inserttime desc ';
        $set[] = $food_id;
        $set[] = $relation_id;
        $set[] = $tag;
        //查询
        $query = $this->db->query($sql, $set);
        //返回
        return $query->row_array();
    }
    /**
     * 得到菜品的评论列表
     *
     * @access public
     * @param mixed $food_id
     * @param int $rows
     * @param int $offset
     * @return void
     */
    public function get_comments_list($food_id,$relation_id,$tag,$rows=20, $offset=0) {
        $this->db->select('fc.id,fc.uid,
                           fc.nickname,fc.grade,
                           fc.content,fc.img,
                           fc.img2,fc.img3,fc.voice,
                           fc.soundlength,fc.inserttime as replydate,
                           u.integrity_level as rank,u.head_portrait ');
        $this->db->from('foodcar_food_comments fc');
        $this->db->join('foodcar_userinfo u', 'fc.uid = u.id', 'left');
        $this->db->where('fc.food_id', $food_id);
        $this->db->where('fc.relation_id', $relation_id);
        $this->db->where('fc.tag',$tag);
        $this->db->order_by('inserttime','desc');
        $this->db->limit($rows,$offset);
        //查询
        $query = $this->db->get();
        //返回
        return $query->result_array();
    }
    /**
     * 得到菜品评论的数量
     *
     * @access public
     * @param mixed $food_id
     * @return void
     */
    public function get_comments_list_total($food_id,$relation_id,$tag) {
        //设置
        $set = array();
        //拼接语句
        $sql = "select count(id) as total
                from foodcar_food_comments
                where food_id = ? and relation_id = ? and tag = ?";
        $set[] = intval($food_id);
        $set[] = $relation_id;
        $set[] = $tag;
        //查询
        $query = $this->db->query($sql, $set);
        //返回总数
        return $query->row()->total;
    }
    /**
     * 添加评论
     *
     * @access public
     * @param array {
     * @param mixed $uid
     * @param mixed $food_id
     * @param int $grade
     * @param string $content}
     * @return void
     */
    public function add_reply($arr_info) {
        //设置
        $this->db->insert('foodcar_food_comments', $arr_info);
        return $this->db->insert_id();
    }
    /**
     * 判断用户是否评论过菜品
     *
     * @access public
     * @param mixed $food_id
     * @param mixed $uid
     * @return void
     */
    public function chk_comment($uid,$food_id,$tag,$relation_id){
        $query = $this->db->get_where('foodcar_food_comments', array('food_id' => $food_id,
                                                                     'uid' => $uid,
                                                                     'relation_id'=>$relation_id,
                                                                     'tag' =>$tag));
        $res_num = $query->num_rows();
        return $res_num;
    }
    /**
     * 更新菜品等级数值
     *
     * @access public
     * @param mixed $food_id
     * @return void
     */
    public function upd_foodgrade($food_id,$tag){
        $this->db->select_avg('grade','aver_grade');
        $query = $this->db->get_where('foodcar_food_comments',array('food_id' => $food_id,'tag' => $tag));
        $aver_grade = $query->row()->aver_grade;
        $aver_grade = round($aver_grade);
        $data = array(
               'aver_grade' => $aver_grade
            );
        $this->db->where('id', $food_id);
        if($tag == 1){
            //$this->db->where('tag', $tag);
            $table = "foodcar_food_info";
        }else{
            $table = "foodcar_official_dish";
        }
        $this->db->update($table, $data);
    }
}