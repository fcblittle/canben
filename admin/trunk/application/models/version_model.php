<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 版本模型
 * 
 * @author sj
 * @package version_model
 */
class Version_model extends CI_Model {

    private $table = '`foodcar_version`';

    //构造
    public function __construct()
    {
        parent::__construct();
    }    

    public function getItemList($args = array())
    {
        $params = array_merge(array(
            ''
        ), $args);

        return $this->db->order_by('id', 'DESC')->get($this->table)->result();
    }

    public function getItem($args)
    {
        $params = array_merge(array(
            'fields'  => '*',
            'id'      => null,
            'appCode' => null,
            'devCode' => null,
            'versionName' => null,
        ), $args);

        $query = $this->db->select($params['fields'])->from($this->table);
        if (! empty($params['id'])) {
            $query->where('id', $params['id']);
        }
        if (! empty($params['appCode'])) {
            $query->where('appCode', $params['appCode']);
        }
        if (! empty($params['devCode'])) {
            $query->where('devCode', $params['devCode']);
        }
        if (! empty($params['versionName'])) {
            $query->where('versionName', $params['versionName']);
        }

        return $query->get()->row();
    }


    /**
     * 得到最新版信息
     * 
     * @access public
     * @return void
     */
    public function get_last_version_info($ver_code,$flag) {
        //语句
        $sql = "select * from foodcar_version where appCode = 1 and devCode = 'Android' order by id desc limit 1";
        $query = $this->db->query($sql);
        //返回
        $res = $query->row_array();
        if($res['id'] == $ver_code){
            $res['versionForcibly'] = 0;
        }elseif ($res['id'] > $ver_code) {
            if($flag){
                $res['versionForcibly'] = 1;
            }else{
                $res['versionForcibly'] = 0;
            }
        }else{
            $res['versionForcibly'] = 0;
        }
        return $res;
    }


    /**
     * 获取app版本信息
     *
     * @default Android端 餐本助手 app 最新版本
     * 
     * @param int appCode app编号
     * @param int devCode 设备编号
     * 
     * @param string versionName 版本号
     * 
     * @return bool
     */
    public function get_latest_merchant_app_version($args)
    {
        $where = array(
            'appCode' => ! empty($args['appCode']) ? $args['appCode'] : 2,
            'devCode' => ! empty($args['devCode']) ? ucfirst($args['devCode']) : 'Android',
        );

        if (! empty($args['versionName'])) {
            $where['versionName'] = $args['versionName'];
        }

        $query = $this->db->where($where)->order_by('id DESC')->get($this->table);

        if ($query->num_rows() == 0) {
            return false;
        }

        return $query->row();
    }

    /**
     * 添加新版本
     */
    public function add($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * 编辑版本信息
     */
    public function edit($data, $id)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    /**
     * 删除版本
     */
    public function delete($id)
    {
        return $this->db->delete($this->table, array('id' => $id));
    }
}