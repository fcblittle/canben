<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 用户模型
 *
 * @author 健
 * @package User_model
 */
class User_model extends CI_Model {
    //常量 盐值
    const salt = 'wooxqwla;fovgjvbwoswiq';
    //构造
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 验证用户登录
     *
     * @access public
     * @param mixed $mobile_phone
     * @param mixed $pwd
     * @return void
     */
    public function check_login($mobile_phone, $pwd) {
        $this->db->select('id,mobile_phone as username,
                           nickname,sex,birthday,
                           email,head_portrait,integrity_level as rank
                         ');
        $query = $this->db->get_where('foodcar_userinfo',array('mobile_phone'=>$mobile_phone,'password'=>$this->password($pwd),'status'=>'1'));
        //用户存在返回用户id，否则返回false
        if (isset($query->row()->id)) {
            return $query->row();
        } else {
            return false;
        }
    }
    /**
     * 根据手机号获得用户信息
     *
     * @access public
     * @param mixed $mobile_phone
     * @return void
     */
    public function get_userinfo_by_mobile_phone($mobile_phone) {
        $this->db->select('id,mobile_phone as username,
                           nickname,sex,birthday,email,head_portrait
                         ');
        $query = $this->db->get_where('foodcar_userinfo',array('mobile_phone'=>$mobile_phone));
        //用户存在返回用户id，否则返回false
        if (isset($query->row()->id)) {
            return $query->row();
        } else {
            return false;
        }
    }
    /**
     * 检查一个手机号是否已注册
     *
     * @access public
     * @param mixed $mobile_phone
     * @return void
     */
    public function check_mobile_phone_exists($mobile_phone) {
        $this->db->select('id');
        $query = $this->db->get_where('foodcar_userinfo', array('mobile_phone' => $mobile_phone));
        //用户存在返回用户id，否则返回false
        if (isset($query->row()->id)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 添加用户
     *
     * @access public
     * @param mixed $mobile_phone
     * @param mixed $pwd
     * @return void
     */
    public function add_user($mobile_phone, $pwd ,$nick_name) {
        if ($this->chk_mobilePwd($mobile_phone,$pwd)) {
            //设置
            $set = array();
            //拼接语句
            $sql = 'insert into foodcar_userinfo (nickname, mobile_phone, password) values (?, ?, ?)';
            $set[] = $nick_name;
            $set[] = $mobile_phone;
            $set[] = $this->password($pwd);
            $this->db->query($sql, $set);
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }
    /**
     * 更新个人信息
     *
     * @access public
     * @param mixed $uid
     * @param mixed $mobile_phone
     * @param mixed $sex
     * @param mixed $birthday
     * @param mixed $email
     * @return void
     */
    public function update_userinfo($uid, $nickname, $sex, $birthday, $email) {
        $data = array(
               'nickname' => $nickname,
               'sex' => $sex,
               'birthday' => $birthday,
               'email' => $email
            );
        $this->db->where('id', $uid);
        $this->db->where('status', '1');
        $this->db->update('foodcar_userinfo', $data);
        return true;
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
        $this->db->update('foodcar_userinfo', $data);
        if ($this->db->affected_rows()) {
             return true;
        }else{
            return false;
        }
    }
    /**
     * 根据手机号设置用户密码
     *
     * @access public
     * @param mixed $mobile_phone
     * @param mixed $pwd
     * @return void
     */
    public function update_pwd_by_mobile_phone($mobile_phone, $pwd) {
        if ($this->chk_mobilePwd($mobile_phone, $pwd)) {
            $data = array('password' => $this->password($pwd));
            $this->db->where('mobile_phone', $mobile_phone);
            $this->db->where('status', '1');
            $this->db->update('foodcar_userinfo', $data);
            if ($this->db->affected_rows()) {
                 return true;
            }else{
                return false;
            }
        } else {
           return false;
        }
    }
    /**
     * 消费者更改密码
     *
     * @access public
     * @param mixed $mobile_phone
     * @param mixed $oldpwd
     * @param mixed $newpwd
     * @return int
     */
    public function upd_userpwd($mobile,$oldpass,$newpwd){
        $res_v = $this->check_login($mobile,$oldpass);
        if($res_v){
            $boolen_str = $this->update_pwd_by_mobile_phone($mobile,$newpwd);
            if ($boolen_str) {
                return 1;
            }else{
                return -1;
            }
        }else{
            return 0;
        }
    }
    /**
     * 登陆时更新authorization验证串
     *
     * @access private
     * @param mixed $mobile_phone
     * @param mixed $pwd
     * @return void
     */
    private function updauth($authorization,$uid) {
        $data = array('authorization' => $authorization);
        $this->db->where('id', $uid);
        $this->db->where('status', '1');
        $this->db->update('foodcar_userinfo', $data);
        return true;
    }
    /**
     * 得到pwd
     *
     * @access private
     * @param mixed $pwd
     * @return void
     */
    private function password($pwd) {
        return md5 ( md5 ( $pwd . self::salt ) );
    }
    /**
     * 生成authorization验证
     *
     * @access public
     * @param mixed $uid
     * @return authorization
     */
    public function getauth($uid){
       $authorization = $uid.time()."FoodCar";
       $authorization = $this->encrypt->encode($authorization);
       $this->updauth($authorization,$uid);
       return $authorization;
    }
    /**
     * 根据authorization验证用户是否登陆
     *
     * @access public
     * @param mixed $authorization
     * @return boolen
     */
    public function checklogin_by_auth($authorization){
        //设置
        $set = array();
        $authorization = str_replace(" ", "+", $authorization);
        //拼接语句
        $sql = 'select id from foodcar_userinfo where authorization = ? and status = 1 ';
        $set[] = $authorization;
        $query = $this->db->query($sql, $set);
        $row_num = $query->num_rows();
        //用户存在返回用户id，否则返回false
        if($row_num){
            $uid = $query->row()->id;
            return $uid;
        }else{
            return false;
        }
    }
     //用户查询
    public function  get_all_userinfo($params = array()){
        $params = array_merge(array(
            'status'  => null,
            'fields'  => '*',
            'name'    => '',
            'nickname'=>'',
            'mobile'=>'',
            'email'=>'',
            'deleted' => false,
            'order'   => 'id DESC',
            'limit'   => 20,
            'page'    => 0,
        ), $params);
        $list = array();
        $conds = '';
        //状态
        if ($params['status'] !== null) {
            $conds .= " AND status = {$params['status']}";
        }
        //用户名称
        if ($params['name'] !== '') {
            $conds .= " AND real_name LIKE '%{$params['name']}%'";
        }
        //用户昵称
        if ($params['nickname'] !== '') {
            $conds .= " AND nickname LIKE '%{$params['nickname']}%'" ;
        }
        //用户电话
        if ($params['mobile'] !== '') {
            $conds .= " AND mobile_phone LIKE '%{$params['mobile']}%'";
        }
        //Email
        if ($params['email'] !== '') {
            $conds .= " AND email = '{$params['email']}'";
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_userinfo`"
            . " WHERE 1 {$conds}"
            . " ORDER BY {$params['order']}"
            . " LIMIT {$params['page']},{$params['limit']}";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum) {
            $list = $query->result();
        }
        $counter = "SELECT COUNT(id) AS count"
            . " FROM `foodcar_userinfo`"
            . " WHERE 1 {$conds}";
        $query = $this->db->query($counter);
        $count = $query->row()->count;

        return array(
            'list' => $list,
            'total' => $count
        );
        /*
        $query = $this->db->get('foodcar_userinfo');
        $rownum = $query->num_rows();
        if($rownum){
            $res = $query->result_array();
            return $res;
        }else{
            return "";
        }*/
    }

    /**
     * 获取多个item
     * @author kevin
     */
    public function get_items($uids, $fields = '*') {
        $ids = is_array($uids) ? implode(',', $uids) : $uids;
        $items = array();
        $sql = "SELECT {$fields}"
            . " FROM `foodcar_userinfo`"
            . " WHERE id IN ({$ids})";
        $q = $this->db->query($sql);
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $v) {
                $items[$v->id] = $v;
            }
        }
        return $items;
    }

     //用户
    public function getinfo_by_userid($table,$id){
        $sql = " SELECT * FROM ".$table." WHERE id = ?";
        $set[] = $id;
        $query = $this->db->query($sql,$set);
        $res = $query->row_array();
        return $res;
    }
    /**
     * 注册的手机存储
     *
     * @access public
     * @param mixed $authorization
     * @return boolen
     */
     public function regcheck($captcha_word,$mobile_phone,$tag){
        $set1 = array();
        //拼接语句
        $sql1 = 'select id from foodcar_regcheck where mobile_phone = ?  and tag = ?';
        $set1[] = $mobile_phone;
        $set1[] = $tag;
        $query = $this->db->query($sql1, $set1);
        $res = $query->row();
        $reg_id = isset($res->id) ? $res->id : 0;
        $set2 = array();
        if($reg_id){
            $sql2 = 'update foodcar_regcheck set  captcha_word = ?  where mobile_phone = ? and tag = ?';
            $set2[] = $captcha_word;
            $set2[] = $mobile_phone;
            $set2[] = $tag;
            $this->db->query($sql2, $set2);
        }else{
            $sql2 = 'insert into foodcar_regcheck(mobile_phone,captcha_word,tag,insertime)values(?,?,?,?)';
            $set2[] = $mobile_phone;
            $set2[] = $captcha_word;
            $set2[] = $tag;
            $set2[] = time();
            $this->db->query($sql2, $set2);
            //return $this->db->insert_id();
        }
     }
    /**
     * 验证短信验证码
     *
     * @access public
     * @param mixed $captcha_word
     * @param mixed $mobile_phone
     * @return boolen
     */
     public function isExist_captcha_word($captcha_word,$mobile_phone, $tag){
        $set3 = array();
        //拼接语句
        $sql3 = 'select id from foodcar_regcheck where mobile_phone = ? and captcha_word = ? and tag = ? ';
        $set3[] = $mobile_phone;
        $set3[] = $captcha_word;
        $set3[] = $tag;
        $query = $this->db->query($sql3, $set3);
        $row_num = $query->num_rows();
        if($row_num){
            $reg_id = $query->row()->id;
            if ($reg_id) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
     }
    /**
     * 根据用户uid获取用户信息
     *
     * @access public
     * @param mixed $uid
     * @return data
     */
     public function get_useinfo_byuid($uid, $fields = '*'){
        $sql = "select {$fields} from foodcar_userinfo where id = '".$uid."'";
        $query = $this->db->query($sql);
        $res = $query->row();
        if(empty($res->nickname)){
            if(empty($res->mobile_phone)){
                return false;
            }else{
                $pattern = "/(1\d{1,2})\d\d(\d{0,3})/";
                $replacement = "\$1*****\$3";
                $res->nickname = preg_replace($pattern, $replacement, $res->mobile_phone);
            }
        }
        return $res;
     }
    /**
     * 更新UID的个人头像
     *
     * @access public
     * @param mixed $uid $head_portrait
     * @return data
     */
    public function upd_person_pic($uid,$head_portrait){
        $update_sql = "update foodcar_userinfo set head_portrait = '".$head_portrait."' where id = '".$uid."'";
        $this->db->query($update_sql);
        if ($this->db->affected_rows()) {
            return 1;
        } else {
            return 0;
        }
    }
    /**
     * 检查注册/重置的用户名和密码不能都是一样的手机号
     *
     * @access public
     * @param mixed $mobile，pwd
     * @return data
     */
    private function chk_mobilePwd($mobile,$pwd){
        if ($mobile == $pwd) {
            return 0;
        } else {
            return 1;
        }
    }
}