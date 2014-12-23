<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 网银支付MD5模型
 *
 * @author 再晨
 * @package Pay_md5
 */
class Pay_md5 extends CI_Model {
    function __construct(){
         parent::__construct();
    }
     //
	function myMd5($text,$key){
		return md5($text.$key);
	}
	//
	function md5_verify($text,$key,$md5){
		$md5Text = $this->myMd5($text,$key);
		return $md5Text==$md5;
	}
}