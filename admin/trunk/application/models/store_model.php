<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 商户模型
 *
 * @author 健
 * @package Store_model
 */
class Store_model extends CI_Model {
    //构造
    public function __construct()    {
        parent::__construct();
    }
    /**
     * 得到商户列表
     *
     * @access public
     * @param int $rows 取记录的行数，默认20
     * @param int $offset 从第几行开始，默认0
     * @param 商户类型 :$store_type
     * @param 关键字 :$kwd
     * @param 范围： $area
     * @param 菜系 :$cuisine
     * @param 排序 :$msort
     * @param 坐标：lat,lon
     * @return void
     * $store_type,$keyword,$area,$cuisine,$msort,$lat,$lon
     */
    public function get_store_list($rows=20, $offset=0, $kwd="",
                                   $area="", $cuisine="", $msort="",$lat="",$lon=""){
        $where = $order = "";
        if(!empty($kwd)){
            $where .= " and store_name like '%".$kwd."%' ";
        }
        if(!empty($cuisine)){
            $where .= " and FIND_IN_SET('".$cuisine."',foodclass) ";
        }
        //距离
        $column_area = " ,ROUND(6378.138*2*ASIN(SQRT(POW(SIN((".$lat."*PI()/180-pointy*PI()/180)/2),2)+
                    COS(".$lat."*PI()/180)*COS(pointy*PI()/180)*
                POW(SIN((".$lon."*PI()/180-pointx*PI()/180)/2),2)))*1000) AS distance ";
        if(!empty($area)){
            $where .= " HAVING distance  < ".$area." ";
        }
        //排序
        if(!empty($msort)){
            $arr_sort = explode("-", $msort);
            $order = " ORDER BY ".$arr_sort[0]." ".$arr_sort[1]." ";
        }
        //拼接语句
        $sql = " SELECT id,merchant_id,store_name as name,address,store_tel as tel,
                        delivery_yorn,condition_and_range,
                        pointx,pointy,store_logo as img ".$column_area."
                 FROM foodcar_store
                 WHERE pointx <> 0 and pointy <> 0 and store_stauts = 1 ".$where." ".$order." limit ".$offset.",".$rows." ";
        //查询
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //返回
        return $result;
    }
    /*
     * 商户类型 :$store_type
     * 关键字 :$kwd
     * 范围： area
     * 菜系 :$cuisine
     * 坐标：lat,lon
   $store_type,$keyword,$area,$cuisine,$lat,$lon
     */
    public function get_store_list_total($kwd="", $area="", $cuisine="",$lat="",
                                         $lon="") {
        $where = "";
        if(!empty($kwd)){
            $where .= " and store_name like '%".$kwd."%' ";
        }
        if(!empty($cuisine)){
            $where .= " and FIND_IN_SET('".$cuisine."',foodclass) ";
        }
        if(!empty($area)){
            $column_area = ",ROUND(6378.138*2*ASIN(SQRT(POW(SIN((".$lat."*PI()/180-pointy*PI()/180)/2),2)+
                                COS(".$lat."*PI()/180)*COS(pointy*PI()/180)*
                            POW(SIN((".$lon."*PI()/180-pointx*PI()/180)/2),2)))*1000) AS distance ";
            $where .= " HAVING distance  < ".$area." ";
        }else{
            $column_area = "";
        }
        //拼接语句
        $sql = " SELECT id,store_name ".$column_area."
                 FROM foodcar_store
                 WHERE pointx <> 0 and pointy <> 0  and store_stauts = 1 ".$where."  ";
        //查询
        $query = $this->db->query($sql);
        //返回总数
        return $query->num_rows();
    }
    /**
     * 得到餐车列表
     *
     * @access public
     * @param int $rows 取记录的行数，默认20
     * @param int $offset 从第几行开始，默认0
     * @param 商户类型 :$store_type
     * @param 关键字 :$kwd
     * @param 范围： $area
     * @param 菜系 :$cuisine
     * @param 排序 :$msort
     * @param 坐标：lat,lon
     * @return void
     * $store_type,$keyword,$area,$cuisine,$msort,$lat,$lon
     */
    // public function get_diner_list($rows=20, $offset=0, $lat="",$lon=""){
    public function get_diner_list($args){
        $where = $order = "";

        $params = array_merge(array(
            'rows'    => 20,
            'offset'  => 0,
            'area_id' => array(),
            'lat'     => "",
            'lon'     => ""
        ), $args);
        extract($params);

        if (! empty($area_id)) {
            $ids = implode(',', $area_id);

            $where .= " AND area IN ($ids)";
        }

        $column_area = " ,ROUND(6378.138*2*ASIN(SQRT(POW(SIN((".$lat."*PI()/180-latitude*PI()/180)/2),2)+
                                COS(".$lat."*PI()/180)*COS(latitude*PI()/180)*
                            POW(SIN((".$lon."*PI()/180-longitude*PI()/180)/2),2)))*1000) AS distance ";
        $order = " ORDER BY distance ASC ";
        //拼接语句
        /*$sql = " SELECT id,merchant_name,diner_name,first_person,first_person_tel,
                        second_person,second_person_tel,car_license_plate,
                        longitude,latitude,description,delivery_yorn,images,
                        condition_and_range,aver_grade,per_capita,collection_num,address
                        $column_area
                 FROM foodcar_diner
                 WHERE longitude <> 0 and latitude <> 0 and
                       store_stauts = 1 ".$where." ".$order." limit ".$offset.",".$rows."";*/

        $sql = " SELECT id,merchant_name,diner_name,car_license_plate,
                        longitude,latitude,description,delivery_yorn,images,
                        condition_and_range,aver_grade,per_capita,collection_num,address
                        $column_area
                 FROM foodcar_diner
                 WHERE longitude <> 0 and latitude <> 0 and
                       store_stauts = 1 ".$where." ".$order." limit ".$offset.",".$rows."";
        //查询
        $query = $this->db->query($sql);
        $diner_list = $query->result_array();
        foreach ($diner_list as $key => $values) {
            $diner_list[$key]['images'] = $values['images']?
                        base_url().stripslashes( $values['images']):
                        base_url().stripslashes('asset/images/diner/diner_icon.png');
            $diner_list[$key]['address'] = $values['address']? $values['address']: '暂无';
        }
         /*
        //判断是否是时间段应该出现的餐车
        $diner_num = count($dinerlist);
        date_default_timezone_set("Etc/GMT-8");
        $time = date('H:i',time());
        $dinerlist = $diner_list = array();
        for ($i=0; $i < $diner_num; $i++) {
            if (!empty($dinerlist1[$i]['trip_time1_end']) &&
                strtotime($dinerlist1[$i]['trip_time1_end']) > strtotime($time)) {
                    $mealtime_id = "1";
            }elseif (!empty($dinerlist1[$i]['trip_time2_end']) &&
                     strtotime($dinerlist1[$i]['trip_time2_end']) > strtotime($time)) {
                    $mealtime_id = "2";
            }else{
                $mealtime_id = "3";
            }
            $query_sql = "select count(od.id) as num
                          from foodcar_official_dish od
                          left join foodcar_food_relation fr
                          on od.id = fr.food_id
                          where fr.diner_id = '".$dinerlist1[$i]['id']."' and od.mealtime_id ='".$mealtime_id."'
                         ";
            $query = $this->db->query($query_sql);
            $num = $query->row()->num;
            if($num >0){
                $dinerlist[] = $dinerlist1[$i];
            }else{
                continue;
            }
        }
        for ($i=$offset; $i < $rows; $i++) {
            if (isset($dinerlist[$i])) {
                 $diner_list[] = $dinerlist[$i];
             }
        }*/
        //返回
        return $diner_list;

    }
     /*
     * 关键字 :$keyword
     */
    public function search_diner_total($keyword) {
        //拼接语句
        $sql = " SELECT `id`,`diner_name`,`address`
                 FROM `foodcar_diner`
                 WHERE `address` like '%$keyword%' and `store_stauts` = '1'  ";
        //查询
        $query = $this->db->query($sql);
        //返回总数
        return $query->num_rows();
    }

    /**
     * 得到餐车列表
     *
     * @access public
     * @param int $rows 取记录的行数，默认20
     * @param int $offset 从第几行开始，默认0
     * @param 关键字 :$keyword
     * @return void
     * $store_type,$keyword,$area,$cuisine,$msort,$lat,$lon
     */
    public function search_diner_list($keyword,$rows=20, $offset=0){
        $order = " ORDER BY id ASC ";
        //拼接语句
        $sql = " SELECT id,merchant_name,diner_name,first_person,first_person_tel,
                        second_person,second_person_tel,car_license_plate,
                        longitude,latitude,trip_time1_start,trip_time1_end,
                        trip_time2_start,trip_time2_end,trip_time3_start,trip_time3_end,
                        description,delivery_yorn,condition_and_range,aver_grade,per_capita,
                        collection_num,address
                 FROM foodcar_diner
                 WHERE address like '%$keyword%' and
                       store_stauts = 1 ".$order." limit ".$offset.",".$rows."";
        //查询
        $query = $this->db->query($sql);
        $diner_list = $query->result_array();
        //返回
        return $diner_list;
    }


    /*
     * 关键字 :$kwd
     * 范围： area
     * 菜系 :$cuisine
     * 坐标：lat,lon
   $store_type,$keyword,$area,$cuisine,$lat,$lon
     */
    public function get_diner_list_total($params = array()) {
        $where = "";
        $set = array();

        extract($params);
        $column_area = ",ROUND(6378.138*2*ASIN(SQRT(POW(SIN((".$lat."*PI()/180-latitude*PI()/180)/2),2)+
                                COS(".$lat."*PI()/180)*COS(latitude*PI()/180)*
                            POW(SIN((".$lon."*PI()/180-longitude*PI()/180)/2),2)))*1000) AS distance ";

        if (! empty($area_id)) {
            $ids = implode(',', $area_id);

            $where .= " AND area IN ($ids)";
        }
        //拼接语句
        $sql = " SELECT id,diner_name $column_area
                 FROM foodcar_diner
                 WHERE longitude <> 0 and latitude <> 0 and store_stauts = 1 ".$where." ";
        //查询
        $query = $this->db->query($sql);
        //返回总数
        return $query->num_rows();
    }
    //
    public function getdiner_byPlateNum($car_license_plate,$lat="",$lon=""){
        $column_area = ",ROUND(6378.138*2*ASIN(SQRT(POW(SIN((".$lat."*PI()/180-latitude*PI()/180)/2),2)+
                        COS(".$lat."*PI()/180)*COS(latitude*PI()/180)*
                        POW(SIN((".$lon."*PI()/180-longitude*PI()/180)/2),2)))*1000) AS distance ";
        $sql = " SELECT id,merchant_name,diner_name,first_person,first_person_tel,
                       second_person,second_person_tel,car_license_plate,
                       longitude,latitude,delivery_yorn,condition_and_range,collection_num,".$column_area;
        $sql .= " FROM foodcar_diner ";
        $sql .= " WHERE car_license_plate = '".$car_license_plate."'";
        //查询
        $query = $this->db->query($sql);
        //返回
        return $query->row_array();
    }
    //
    public function get_all_merchant(){
        $query = $this->db->get('foodcar_merchant');
        $rownum = $query->num_rows();
        if($rownum){
            $res = $query->result_array();
            return $res;
        }else{
            return 0;
        }
    }
    /**
     * 获取列表
     *
     * @access public
     * @return mixed
     */
    public function get_list($params = array()) {
        $params = array_merge(array(
            'fields'  => '*',
            'status'  => null,
            'name'    => '',
            'deleted' => false,
            'order'   => 'id DESC',
            'limit'   => 20,
            'page'    => 0
        ), $params);
        $list = array();
        $conds = '';
        if ($params['status'] !== null) {
            $conds .= " AND status = {$params['status']}";
        } else {
            $conds .= " AND status <> -1";
        }
        if ($params['name'] !== '') {
            $conds .= " AND merchant_name LIKE '%{$params['name']}%'";
        }
        if ($params['legal_represent'] !== '') {
            $conds .= " AND legal_represent LIKE '%{$params['legal_represent']}%'";
        }
        if ($params['mobile'] !== '') {
            $conds .= " AND mobile = '{$params['mobile']}'";
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_merchant`"
            . " WHERE 1 {$conds}"
            . " ORDER BY {$params['order']}"
            . " LIMIT {$params['page']},{$params['limit']}";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum) {
            $list = $query->result();
        }
        $counter = "SELECT COUNT(id) AS count"
            . " FROM `foodcar_merchant`"
            . " WHERE 1 {$conds}";
        $query = $this->db->query($counter);
        $count = $query->row()->count;

        return array(
            'list' => $list,
            'total' => $count
        );
    }
    //
    public function get_all_storelist($params = array(),$merchantid=0){
         $params = array_merge(array(
            'fields'  => '*',
            'status'  => null,
            'name'    => '',
            'deleted' => false,
            'order'   => 'id DESC',
            'limit'   => 20,
            'page'    => 0
        ), $params);
        $list = array();
        $conds = '';
        if ($params['status'] !== null) {
            $conds .= " AND store_stauts = {$params['status']}";
        }
        if ($params['name'] !== '') {
            $conds .= " AND store_name LIKE '%{$params['name']}%'";
        }
        if ($params['mobile'] !== '') {
            $conds .= " AND store_tel = '{$params['mobile']}'";
        }
        if($merchantid){
            $conds .= " AND merchant_id = '{$merchantid}'";
        }
        $sql = "SELECT {$params['fields']}"
            . " FROM `foodcar_store`"
            . " WHERE 1 {$conds} "
            . " ORDER BY {$params['order']}"
            . " LIMIT {$params['page']},{$params['limit']}";
        $query = $this->db->query($sql);
        $rownum = $query->num_rows();
        if($rownum) {
            $list = $query->result();
        }
        $counter = "SELECT COUNT(id) AS count"
            . " FROM `foodcar_store`"
            . " WHERE 1 {$conds}";
        $query = $this->db->query($counter);
        $count = $query->row()->count;
        return array(
            'list' => $list,
            'total' => $count
        );
    }
    //
    public function get_all_store($merchantid=0){
        if($merchantid){
            $this->db->where('merchant_id', $merchantid);
        }
        $query = $this->db->get('foodcar_store');
        $res = $query->result_array();
        return $res;
    }
    //
    public function get_all_car($merchantid){
        $this->db->select('id, merchant_id, merchant_name,
                           diner_name,first_person,first_person_tel,
                           car_license_plate,trip_time1_start,trip_time1_end,
                           trip_time2_start,trip_time2_end,trip_time3_start,
                           trip_time3_end,store_stauts
                         ');
        if($merchantid){
            $this->db->where("merchant_id",$merchantid);
        }
        $query = $this->db->get('foodcar_diner');
        $res = $query->result_array();
        return $res;
    }
    //
    public function store_and_diner($selected){
        echo $selected."<br/>";
        if($selected == '_0'){
            $html = " <option value='0' selected='selected'>官方</option>";
        }else{
            $html = " <option value='0'>官方</option>";
        }
        $store_arr = $this->get_all_store(0);
        foreach($store_arr as $v){
            $str = ($selected == 'store_'.$v['id']) ? 'selected' : '';
            $html .= "     <option value='store_".$v['id']."'".$str.">".$v['store_name']."</option>";
        }
        $diner_arr = $this->get_all_car(0);
        foreach($diner_arr as $x){
            $str = ($selected == 'diner_'.$x['id']) ? 'selected' : '';
            $html .= "     <option value='diner_".$x['id']."'".$str.">".$x['diner_name']."</option>";
        }
        return $html;
    }
    //
    public function get_dinerinfo_byid($dinerid){
        $query = $this->db->get_where('foodcar_diner', array('id' => $dinerid));
        $row = $query->row_array();
        return $row;
    }

    /**
     * 获取餐车经营者
     */
    public function get_diner_manager($diner_id)
    {
        $sql = "SELECT * 
                FROM `biz_staff` 
                WHERE role=1 
                AND diner_id=?";

        $res = $this->db->query($sql, array($diner_id));

        return $res->row_array();
    }

    //
    public function get_valName($val,$table,$where){
        $sql = "SELECT ".$val." FROM ".$table." WHERE ".$where;
        $query = $this->db->query($sql);
        $res = $query->row_array();
        $name = isset($res[$val])?$res[$val]:"";
        return $name;
    }
    /**
     * 得到餐厅的详细信息
     *
     * @access public
     * @param mixed $store_id
     * @return void
     */
    public function get_store_info($store_id) {
        //设置
        $set = array();
        //拼接语句
        $sql = 'select id,
                    store_name,
                    address,
                    store_tel as tel,pointx,
                    pointy,store_images as img,
                    store_feature,store_atmosphere,
                    description,store_hours_start,
                    store_hours_end,store_feature,
                    store_atmosphere,bus,
                    avg_taste as taste,
                    avg_atmosphere as atmosphere,
                    avg_service as service,
                    per_capita,
                    comment_num,
                    promotion
                from foodcar_store
                where merchant_id = ? ';
        $sql .= ' limit 1';
        $set[] = $store_id;
        //查询
        $query = $this->db->query($sql, $set);
        $store_info = $query->row_array();
        if (count($store_info) < 1 ){
            return array();
        }else{
            $store_info['img'] = MERCHANT_URL.$store_info['img'];
            $store_info['restaurants'] = $this->get_dinerlist_by_id($store_id);
            $store_feature_arr = explode(",", $store_info['store_feature']);
            $count_label = count(array_filter($store_feature_arr));
            if ($count_label) {
                $store_info['store_feature'] = $this->get_storelabel_byid($store_feature_arr);
            }
            //返回
            return $store_info;
        }
        //------------得到一条评论
        //$this->load->model('store_comments_model', 'comments', true);
        //$store_info['comments'] = $this->comments->get_last_comment($store_id);
        //------------得到餐厅下的餐车      第一个参数 100000 其目的是为了 搜索全部列表内容
    }
    /**
     * 根据餐厅id获得餐车列表
     *
     * @access public
     * @param mixed $merchant_id //商户ID
     * @return void
     */
    public function get_dinerlist_by_id($merchant_id) {
        $query = $this->db->get_where('foodcar_diner', array('merchant_id' => $merchant_id));
        $res = $query->result_array();
        return $res;
    }
    /**
     * 根据store_id得到商铺地址
     *
     * @access public
     * @param mixed $store_id
     * @return void
     */
    /*public function get_address_by_store_id($store_id) {
        //设置
        $set = array();
        //设置sql语句
        $sql = 'select address from foodcar_store where 1 and id = ?';
        $set[] = $store_id;
        //查询
        $query = $this->db->query($sql, $set);
        //返回
        return isset($query->row()->address) ? $query->row()->address : '';
    }*/
    /**
     * 根据store_id得到商铺名称
     *
     * @access public
     * @param mixed $store_id
     * @return void
     */
    /*public function get_name_by_store_id($store_id) {
        //设置
        $set = array();
        //设置sql语句
        $sql = 'select store_name from foodcar_store where 1 and id = ?';
        $set[] = $store_id;
        //查询
        $query = $this->db->query($sql, $set);
        //返回
        return isset($query->row()->store_name) ? $query->row()->store_name : '';
    }*/
    /**
     * 根据store_id得到商铺特定信息
     *
     * @access public
     * @param mixed $store_id
     * @param mixed $fieldstr
     * @return void
     */
    public function getinfo_by_storeid($merchant_id,$fieldstr) {
        //设置
        $set = array();
        //设置sql语句
        $sql = 'select '.$fieldstr.' from foodcar_store where merchant_id = ?';
        $set[] = $merchant_id;
        //查询
        $query = $this->db->query($sql, $set);
        $storeinfolist = $query->row_array();
        //返回
        return $storeinfolist;
    }
    /**
     * 根据merchantid得到商铺特定信息
     *
     * @access public
     * @param mixed $store_id
     * @param mixed $fieldstr
     * @return void
     */
    public function getinfo_by_merchantid($merchantid,$fieldstr) {
        //设置
        $set = array();
        //设置sql语句
        $sql = 'select '.$fieldstr.' from foodcar_merchant where id = ?';
        $set[] = $merchantid;
        //查询
        $query = $this->db->query($sql, $set);
        $infolist = $query->row_array();
        //返回
        return $infolist;
    }
    /**
     * 根据商户id得到商户类型
     *
     * @access public
     * @param mixed $store_id
     * @return void
     */
    /*public function get_store_style_by_store_id($store_id) {
        //设置
        $set = array();
        //设置sql语句
        $sql = 'select store_style from foodcar_store where 1 and id = ?';
        $set[] = $store_id;
        //查询
        $query = $this->db->query($sql, $set);
        //返回
        return $query->row()->store_style;
    }*/
    /**
     * 商户登录
     *
     * @access public
     * @param mixed $merchant_login
     * @param mixed $merchant_pwd
     * @return void
     */
    public function store_login($merchant_login,$merchant_pwd) {
        //设置
        $set = array();
        // 商户
        $sql = 'select id as merchant_id,merchant_login,merchant_name,merchant_pwd,status
                from foodcar_merchant
                where merchant_login=?';
        $set[] = $merchant_login;
        $query = $this->db->query($sql, $set);
        $res = $query->row_array();
        $rownum = $query->num_rows();
        if($rownum){
            $db_pwd = $this->encrypt->decode($res['merchant_pwd']);
            if($db_pwd == $merchant_pwd && $res['status'] == 1){
                $data['id'] = $res['merchant_id'];
                $data['type'] = 'merchant';
                $data['login'] = $res['merchant_login'];
                $data['name'] = $res['merchant_name'];
                // $data['role'] = $res['role'];
                return $data;
            } else if ($res['status'] == 2) {
                return array(
                    'status' => 'ban',
                    'code'   => -26
                );
            }
        }

        // 经营者
        $sql_m = 'SELECT staff.id, staff.diner_id, staff.merchant_id, staff.username, staff.salt, staff.pass,staff.realname, staff.status, diner.role
                  FROM `biz_staff` AS staff
                  LEFT JOIN `foodcar_diner` AS diner
                  ON staff.diner_id = diner.id
                  WHERE staff.role=1 
                  AND staff.username=?';
        $query_m = $this->db->query($sql_m, array($merchant_login));
        $res_m = $query_m->row_array();
        $row_num_m = $query_m->num_rows();
        if ($row_num_m) {
            $password = $this->encrypt->password($merchant_pwd, $res_m['salt']);
            if ($password == $res_m['pass'] && $res_m['status'] == 1) {
                $data['id'] = $res_m['id'];
                $data['type'] = 'manager';
                $data['dinerId'] = $res_m['diner_id'];
                $data['merchantId'] = $res_m['merchant_id'];
                $data['login'] = $res_m['username'];
                $data['name'] = $res_m['realname'];
                $data['status'] = $res_m['status'];
                $data['role'] = $res_m['role'];

                return $data;
            } else if ($res_m['status'] == 0) {
                return array(
                    'status' => 'ban',
                    'code'   => -25
                );
            } else {
                return false;
            }
        }

        return false;
    }
    /**
     * 根据商户ID判断
     *
     * @access public
     * @return void
     */
    public function chk_merchant_byid($merchantid,$merchant_pwd){
        $this->db->select('merchant_pwd');
        $query = $this->db->get_where('foodcar_merchant', array('id' => $merchantid));
        $res = $query->row_array();
        $rownum = $query->num_rows();
        if($rownum){
            $db_pwd = $this->encrypt->decode($res['merchant_pwd']);
            if($db_pwd == $merchant_pwd){
                return true;
            }else{
                //商户不存在或密码不正确
                return false;
            }
        }else{
            return false;
        }
    }
    /**
     * 商户特点
     *
     * @access public
     * @return void
     */
     public function  get_storelabel(){
        //$sql = "SELECT * FROM foodcar_storelabel ORDER BY sort asc ";
        //$query = $this->db->query($sql);
        $query = $this->db->get('foodcar_storelabel');
        $res = $query->result_array();
        return $res;
     }
     //
     public function get_storelabel_byid($label_arr){
        $count_label = count($label_arr);
        $store_label_str = "";
        for ($i=0; $i < $count_label; $i++) {
            $sql = "select slable_name from foodcar_storelabel where id = '".$label_arr[$i]."'";
            $query = $this->db->query($sql);
            $store_label = $query->row_array();
            $store_label_str .= $store_label['slable_name'].",";
        }
        return $store_label_str;
     }
    /**
     * 商户特点 下拉列表
     *
     * @access public
     * @return void
     */
     public function gethtml_storelabel($chkoption=""){
        $html = "";
        $label_arr = $this->get_storelabel();
        if($chkoption){
            $option_arr = array_filter(explode(",",$chkoption));
            foreach($label_arr as $v){
                if(in_array($v['id'], $option_arr)){
                    $html .= "     <option selected value='".$v['id']."'>".$v['slable_name']."</option>";
                }else{
                    $html .= "     <option value='".$v['id']."'>".$v['slable_name']."</option>";
                }
            }
        }else{
            foreach($label_arr as $v){
                    $html .= "     <option value='".$v['id']."'>".$v['slable_name']."</option>";
            }
        }
        return $html;
     }
    /**
     * 菜品标签
     *
     * @access public
     * @return void
     */
     public function  get_foodlabel(){
        //$sql = "SELECT * FROM foodcar_foodlabel ORDER BY sort asc ";
        //$query = $this->db->query($sql);
        $query = $this->db->get('foodcar_foodlabel');
        $res = $query->result_array();
        return $res;
     }
     //
     public function gethtml_foodlabel($chkoption=""){
        $html = "";
        $label_arr = $this->get_foodlabel();
        if($chkoption){
            $option_arr = array_filter(explode(",",$chkoption));
            foreach($label_arr as $v){
                if(in_array($v['id'], $option_arr)){
                    $html .= "     <option selected value='".$v['id']."'>".$v['flabel_name']."</option>";
                }else{
                     $html .= "     <option value='".$v['id']."'>".$v['flabel_name']."</option>";
                }
            }
        }else{
            foreach($label_arr as $v){
                $html .= "     <option value='".$v['id']."'>".$v['flabel_name']."</option>";
            }
        }
        return $html;
     }
    /**
     * 获取商户下的菜品列表
     *
     * @access public
     * @return void
     */
     public function get_foodlist_byid($id){
        //$sql = " SELECT * FROM foodcar_food_info WHERE merchant_id = '".$id."' ";
        //$query = $this->db->query($sql);
        $query = $this->db->get_where('foodcar_food_info', array('merchant_id' => $id));
        $res = $query->result_array();
        return $res;
     }
    /**
     * 根据ralation表获得列表
     *
     * @access public
     * @return void
     */
    public function get_foodlist_byrelation($data,$tag,$merchantid,$id){
        $count = count($data);
        $option_arr = $this->get_ralation($tag,$merchantid,$id);
        $option = array();
        foreach ($option_arr as $v) {
            $option[] = $v['food_id'];
        }
        for ($i=0; $i < $count; $i++) {
            if(in_array($data[$i]['id'], $option)){
                $data[$i]['checked'] = " checked='checked' ";
            }else{
                $data[$i]['checked'] = "";
            }
        }
        return $data;
    }
    /**
     * 根据id活动菜品信息
     *
     * @access public
     * @return void
     */
     public function getinfo_by_foodid($id){
        //$sql = " SELECT * FROM foodcar_food_info WHERE id = '".$id."'";
        //$query = $this->db->query($sql);
        $query = $this->db->get_where('foodcar_food_info', array('id' => $id));
        $foodinfo = $query->row_array();
        return $foodinfo;
     }
    /**
     * 菜品分类
     *
     * @access public
     * @return void
     */
     public function  get_foodclass(){
        //$sql = "SELECT * FROM foodcar_foodlabel ORDER BY sort asc ";
        //$query = $this->db->query($sql);
        $this->db->order_by("sort", "asc");
        $query = $this->db->get('foodcar_foodlabel');
        $res = $query->result_array();
        return $res;
     }
    /**
     * 菜品分类 下拉列表
     *
     * @access public
     * @return void
     */
     public function gethtml_foodclass($chkoption=""){
        $html = "";
        $label_arr = $this->get_foodclass();
        if($chkoption){
            $option_arr = array_filter(explode(",",$chkoption));
            foreach($label_arr as $v){
                if(in_array($v['id'], $option_arr)){
                    $html .= "     <option selected value='".$v['id']."'>".$v['flabel_name']."</option>";
                }else{
                    $html .= "     <option value='".$v['id']."'>".$v['flabel_name']."</option>";
                }
            }
        }else{
            foreach($label_arr as $v){
                $html .= "     <option value='".$v['id']."'>".$v['flabel_name']."</option>";
            }
        }
        return $html;
     }
    /**
     * 计算地球弧度
     *
     * @access public
     * 参数：经纬度
     * @return 弧度
     */
    function rad($d){
        return $d * 3.1415926535898 / 180.0;
    }
    /**
     * 计算经纬度之间的距离
     *
     * @access public
     * 参数：经纬度
     * @return 距离
     */
    function GetDistance($lat1, $lng1, $lat2, $lng2){
        $EARTH_RADIUS = 6378.137;
        $radLat1 = $this->rad($lat1);
        //echo $radLat1;
        $radLat2 = $this->rad($lat2);
        $a = $radLat1 - $radLat2;
        $b = $this->rad($lng1) - $this->rad($lng2);
        $s = 2 * asin(sqrt(pow(sin($a/2),2) +
        cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
        $s = $s *$EARTH_RADIUS;
        $s = round($s * 10000) / 10000;
        return $s;
    }
    /**
     * 获得官方餐车上的菜系
     *
     * @access public
     * @return void
     */
     public function  get_cuisine(){
        //$sql = "SELECT * FROM foodcar_foodclass ORDER BY sort asc ";
        //$query = $this->db->query($sql);
        $this->db->select('id, classname as name, sort');
        $this->db->order_by("sort", "asc");
        $query = $this->db->get('foodcar_foodclass');
        $res = $query->result_array();
        return $res;
     }
    /**
     * 获得官方餐车上的菜系 下拉列表
     *
     * @access public
     * @return void
     */
     public function gethtml_cuisine($chkoption=""){
        $html = "";
        $label_arr = $this->get_cuisine();
        if($chkoption){
            $option_arr = array_filter(explode(",",$chkoption));//
            $for_num = count($option_arr);
            foreach($label_arr as $v){
                if(in_array($v['id'], $option_arr)){
                    $html .= "<input type=\"checkbox\" name=\"foodclass[]\" checked=\"checked\" value=\"".$v['id']."\"/>".$v['name']." ";

                }else{
                    $html .= "<input type=\"checkbox\" name=\"foodclass[]\" value=\"".$v['id']."\"/>".$v['name']." ";
                }
            }
        }else{
            foreach($label_arr as $v){
                $html .= "     <input type=\"checkbox\" name=\"foodclass[]\" value=\"".$v['id']."\"/>".$v['name']." ";
            }
        }
        return $html;
     }
    /**
     * 获得官方餐厅上的菜系
     *
     * @access public
     * @return void
     */
     public function  get_cuisine2(){
        $this->db->select('id, cuisine as name, sort');
        $this->db->order_by("sort", "asc");
        $query = $this->db->get('foodcar_cuisine');
        $res = $query->result_array();
        return $res;
     }
     /**
     * 获得官方餐厅上的菜系 下拉列表
     *
     * @access public
     * @return void
     */
     public function gethtml_cuisine2($chkoption=""){
        $html = "";
        $label_arr = $this->get_cuisine2();
        if($chkoption){
            $option_arr = array_filter(explode(",",$chkoption));//
            $for_num = count($option_arr);
            foreach($label_arr as $v){
                if(in_array($v['id'], $option_arr)){
                    $html .= "<input disabled type=\"checkbox\" name=\"foodclass[]\" checked=\"checked\" value=\"".$v['id']."\"/>".$v['name']." ";

                }else{
                    $html .= "<input disabled type=\"checkbox\" name=\"foodclass[]\" value=\"".$v['id']."\"/>".$v['name']." ";
                }
            }
        }else{
            foreach($label_arr as $v){
                $html .= "     <input disabled type=\"checkbox\" name=\"foodclass[]\" value=\"".$v['id']."\"/>".$v['name']." ";
            }
        }
        return $html;
     }

    /**
     * 判断商户登录名是否存在
     *
     * @access public
     * @param  $store_login_name
     * @return boolean
     */
    public function chk_StoreLoginName($merchant_login,$merchantid=0){
        $sql = " select id from foodcar_merchant where merchant_login ='".$merchant_login."' ";
        $query = $this->db->query($sql);
        $res = $query->row();
        $num = $query->num_rows();
        if($num){
            if($res->id == $merchantid){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
    /**
     * 更新商户密码
     *
     * @access public
     * @param  $store_login_name
     * @return boolean
     */
    public function upd_storepwd($merchantid,$merchant_newpwd){
        $data = array(
                       'merchant_pwd' => $this->encrypt->encode($merchant_newpwd)
                    );
        $this->db->where('id', $merchantid);
        $this->db->update('foodcar_merchant', $data);
        //echo $this->db->last_query();
    }
 /**
     * 商户
     *
     * @access public
     * @return void
     */
     public function  get_merchant(){
        $sql = "SELECT * FROM foodcar_merchant ORDER BY id asc ";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res;
     }

    //菜品属于商户
     public function gethtml_merchant($chkoption=""){
        $html = "";
        $label_arr = $this->get_merchant();
        if($chkoption){
            $option_arr = array_filter(explode(",",$chkoption));
            foreach($label_arr as $v){
                if(in_array($v['id'], $option_arr)){
                    $html .= "     <option selected value='".$v['id']."'>".$v['merchant_name']."</option>";
                }else{
                     $html .= "     <option value='".$v['id']."'>".$v['merchant_name']."</option>";
                }
            }
        }else{
            foreach($label_arr as $v){
                $html .= "     <option value='".$v['id']."'>".$v['merchant_name']."</option>";
            }
        }
        return $html;
     }
    /**
     * 判断手机号
     *
     * @access public
     * @param  $mobilephone
     * @return boolean
     */
    function checkMobile($mobilephone){
        if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/",$mobilephone)){
            return 1;
        }else{
            return 0;
        }
    }
    private function get_ralation($tag,$merchantid,$id){
        $tag = ($tag == 'store') ? 1 : 2;
        $this->db->select('food_id');
        $query = $this->db->get_where('foodcar_food_relation', array('diner_id'=>$id,'merchant_id'=>$merchantid));
        $res = $query->result_array();
        return $res;
    }
    //判断餐车是否正常营业状态
    public function check_diner($diner_id){
        $this->db->select('longitude, latitude, store_stauts');
        $query = $this->db->get_where('foodcar_diner', array('id' => $diner_id));
        $row = $query->row();
        $sel_num = $query->num_rows();
        if($sel_num){
            if (empty($row->longitude) || empty($row->latitude) || $row->store_stauts != "1") {
                return 0;
            }else{
                return 1;
            }
        }else{
            return 0;
        }
    }
    //获取商户的报表
    public function get_report($diner_id) {
        $before_day_time = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
        $mid_time = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $today_time = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
        $report_manager = array();
        //当天成交量 交易额sql
        $sql_1 = " select sum(order_count) as volume, sum(order_amount) as trading 
                   from foodcar_order 
                   where store_id = '".$diner_id."' and insert_time between ".$mid_time." and ".$today_time."
                 ";
//echo "sql_1:".$sql_1."<br/>";                 
        $query1 = $this->db->query($sql_1);
        $res1 = $query1->row(); 
        //昨日成交量 交易额sql
        $sql_2 = " select sum(order_count) as volume, sum(order_amount) as trading 
                   from foodcar_order 
                   where store_id = '".$diner_id."' and insert_time between ".$before_day_time." and ".$mid_time."
                 ";
//echo "sql_2:".$sql_2."<br/>";  
        $query2 = $this->db->query($sql_2);
        $res2 = $query2->row();

        $today_anticipated_profit = 0;//当天的销售利润
        $yestoday_anticipated_profit = 0;//昨天的销售利润
        $expected_profit_total = 0;
        //当天销售利润
        $sql_food1 = " SELECT o.id,fo.food_id,fo.food_name,SUM(num) as food_num, 
                              fo.supply_price,fo.unit_price 
                       FROM foodcar_order o 
                       LEFT JOIN foodcar_food_order fo 
                       ON o.id = fo.order_id
                       WHERE o.store_id = '".$diner_id."' and fo.id is not null and
                             o.insert_time between ".$mid_time." and ".$today_time." 
                       GROUP BY fo.food_id ";  
//echo "sql_food1".$sql_food1."<br/>";                                             
        $query1 = $this->db->query($sql_food1); 
        if ($query1->num_rows() > 0){
           foreach ($query1->result() as $row){
              $today_anticipated_profit += ((float)$row->unit_price * (int)$row->food_num) - ((float)$row->supply_price * (int)$row->food_num);
           }
        }
        //昨日销售利润
        $sql_food2 = " SELECT o.id,fo.food_id,fo.food_name,SUM(num) as food_num, 
                              fo.supply_price,fo.unit_price 
                       FROM foodcar_order o 
                       LEFT JOIN foodcar_food_order fo 
                       ON o.id = fo.order_id
                       WHERE o.store_id = '".$diner_id."' and fo.id is not null and
                             o.insert_time between ".$before_day_time." and ".$mid_time."  
                       GROUP BY fo.food_id ";
//echo "sql_sql_food2food1".$sql_food2."<br/>"; 
        $query2 = $this->db->query($sql_food2); 
        if ($query2->num_rows() > 0){
           foreach ($query2->result() as $row){
              $yestoday_anticipated_profit += ((float)$row->unit_price * (int)$row->food_num) - ((float)$row->supply_price * (int)$row->food_num);
           }
        } 
        //
        $report_manager["today"] = array();
        $report_manager["today"]["volume"] = (int)$res1->volume;//成交量
        $report_manager["today"]["trading"] = (float)$res1->trading;//交易额
        $report_manager["today"]["anticipated_profit"] = $today_anticipated_profit;//销售利润
        //
        $report_manager["yestoday"] = array();
        $report_manager["yestoday"]["volume"] = (int)$res2->volume;//成交量
        $report_manager["yestoday"]["trading"] = (float)$res2->trading;//交易额
        $report_manager["yestoday"]["anticipated_profit"] = $yestoday_anticipated_profit;//预计利润
        //
        $query3 = $this->db->get_where('foodcar_bankbook', array('diner_id' => $diner_id));
        $store_book_num = $query3->num_rows();
        //判断商户存款表里面有没有数据
        if ($store_book_num > 0){
            //判断数据库今天有没有存入记录
            $sql_check = " select id,balance 
                           from foodcar_bankbook 
                           where diner_id = '".$diner_id."' and type = 2 
                                 and date between ".$mid_time." and ".$today_time."";
//echo "sql_check".$sql_check."<br/>";
            $query_check = $this->db->query($sql_check); 
            $row_check = $query_check->row_array();
            //获取昨天，也是最近的数据库里面的 之前的余额
            $sql_sel = " select balance 
                         from foodcar_bankbook 
                         where diner_id = '".$diner_id."' ORDER BY id DESC ";
//echo "sql_sel".$sql_sel."<br/>";
            $query_sel = $this->db->query($sql_sel);
            $row_sel = $query_sel->row_array(0); 
            $balance = (empty($row_sel['balance'])) ? 0.00 : $row_sel['balance'];
            //
            if (($balance == 0) || ($balance == 0.00)) {
                //意味着今天数据为第一次数据，没有昨天的利润，今天的利润即为全部利润
                $expected_profit_total = (empty($row_check['balance'])) ? 0.00 : $row_check['balance'];
                if (!$expected_profit_total) {
                  $expected_profit_total = $today_anticipated_profit;
                }
            }else{
                  if ($query_check->num_rows > 0) {
                    //如果今天的数据存在即为更新
                    //每次调用都将原来的数据覆盖，避免重复相加
                    $sql_upd = "update foodcar_bankbook set 
                                       balance = ".$balance." + ".$today_anticipated_profit.", 
                                       money = ".$today_anticipated_profit." 
                                where diner_id = '".$diner_id."' and type = 2 
                                     and date between ".$mid_time." and ".$today_time." ";
    //echo "sql_upd".$sql_upd."<br/>";
                    $query_upd = $this->db->query($sql_upd); 
                    $expected_profit_total = $balance + $today_anticipated_profit;
                }else{
                    //如果今天的时间为空，即为新增一条当天的记录
                    $data = array(
                                   'diner_id' => $diner_id,
                                   'balance' => $balance + $today_anticipated_profit,
                                   'type' => 2,
                                   'money' => $today_anticipated_profit,
                                   'date' => time()
                                );
                    $this->db->insert('foodcar_bankbook', $data); 
                    $expected_profit_total = $balance + $today_anticipated_profit;
                }        
            }  
        }else{//如果账户表里面没有 商户的账号信息，则为第一次添加
            $anticipated_profit = 0;
            $sql_all = "   SELECT o.id,fo.food_id,fo.food_name,num as food_num, 
                                  fo.supply_price,fo.unit_price 
                           FROM foodcar_order o 
                           LEFT JOIN foodcar_food_order fo 
                           ON o.id = fo.order_id
                           WHERE o.store_id = '".$diner_id."' and fo.id is not null   
                         ";
//echo "sql_all".$sql_all."<br/>";
            $query = $this->db->query($sql_all); 
            if ($query->num_rows() > 0){
               foreach ($query->result() as $row){
                  $anticipated_profit += ((float)$row->unit_price * (int)$row->food_num) - ((float)$row->supply_price * (int)$row->food_num);
               }
            } 
            $data = array(
                           'diner_id' => $diner_id,
                           'balance' => $anticipated_profit,
                           'type' => 2,
                           'money' => $anticipated_profit,
                           'date' => time()
                        );
            $this->db->insert('foodcar_bankbook', $data); 
            $expected_profit_total = $anticipated_profit;
        }
        $report_manager["expected_profit_total"] = $expected_profit_total;  
        //echo $expected_profit_total."<br/>";
        return $report_manager;    
    }


    /**
     * 验证餐车经营者登陆账号唯一值
     */
    public function checkManagerLoginUnique($manager_login, $diner_id, $chk_type = 'add')
    {
        if ($chk_type == 'add') {
            // TODO: form_validation is_unique
        }
        if ($chk_type == 'edit') {
            $sqlb = "SELECT diner_id 
                    FROM `biz_staff` 
                    WHERE username=? 
                    LIMIT 1";

            $query = $this->db->query($sqlb, $manager_login);
            $resb = $query->row_array();
            if (($query->num_rows() > 0) && ($resb['diner_id'] != $diner_id)) {
                return false;
            }

            $sqlm = "SELECT id
                    FROM `foodcar_merchant`
                    WHERE merchant_login=? 
                    LIMIT 1";
            $resm = $this->db->query($sqlm, $manager_login);
            if ($resm->num_rows() > 0) {
                return false;
            }

            return true;
        }
    }
}