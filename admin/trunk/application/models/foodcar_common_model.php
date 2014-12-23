<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 公共模型
 *
 * @author 再晨
 * @package foodcar_common_model
 */
 class Foodcar_common_model extends CI_Model {
     //构造
    public function __construct(){
        parent::__construct();
    }
    /**
     * 获取公共模板加载
     *
     * @access public
     * @return void
     */
    public function get_comhtml($active,$view,$temp){
        //$temp["data_list"] = $temp;
        //加载样式
        $admini_head = $this->load->view('admini_t/admini_head','',true);
        $admini_nav = $this->load->view('admini_t/admini_nav','',true);
        $temp2["active"] = $active;
        $admin_sidebar = $this->load->view('admini_t/admin_sidebar',$temp2,true);
        //加载页脚
        $footer = $this->load->view('admini_t/footer','',true);
        $temp["admini_head"] = $admini_head;
        $temp["admini_nav"] = $admini_nav;
        $temp["admin_sidebar"] = $admin_sidebar;
        $temp["footer"] = $footer;
        $this->load->view($view,$temp);
    }

 }