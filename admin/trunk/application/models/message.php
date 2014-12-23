<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message extends CI_Model {

    function __construct()
     {
         parent::__construct();
     }
    function showmessage($msg)
    {
            /*if($goto == '')
            {
                $goto = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url();
            }
            elseif($goto=='-2'){
                $goto = "javascript:history.go(-2);";
            }
            else
            {
                $goto = site_url($goto);
            }*/
            //¼ÓÔØÒ³½Å
            //$footer = $this->load->view('admini_t/footer','',true);
            //$msg["footer"] = $footer;
            //$errMsg = $msg;
            $temp['content'] = $msg;
            $this->load->view('admini_t/error405',$temp);
            echo $this->output->get_output();
            exit();
    }
    function showAffairs_message($msg, $goto = '',$auto = true)
    {
            if($goto == ''){
                $goto = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url();
            }elseif($goto=='-2'){
                $goto = "javascript:history.go(-2);";
            }else{
                $goto = site_url($goto);
            }
            $this->load->view('admini_t/body_message',array('msg'=>$msg,'goto'=>$goto,'auto'=>$auto));
            echo $this->output->get_output();
            exit();
    }
}

/* End of file message.php */
/* Location:  */