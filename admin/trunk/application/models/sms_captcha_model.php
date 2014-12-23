<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 短信验证码模型
 * 
 * @author sj
 * @package Sms_captcha_model
 */
class Sms_captcha_model extends CI_Model {
    
    private $uid = 'qddgrj';	//用户账号
    private $pwd = 'B7D33A9725DA7C36270B4E060E65CF89';	//用户密码
    private $http = 'http://60.209.7.12:8080/smsServer/submit';		//发送地址
    //public $mobile	 = '13969832203'; //多个号码之间用","分隔 
    //public $content = '';	//发送内容

    //构造
    public function __construct()
    {
        parent::__construct();
    }
    
    public function sendSMS($mobile,$content,$time='',$mid='')
    {
        $data = array
            (
            'CORPID'=>$this->uid,				//用户账号
            'CPPW'=>$this->pwd,				//密码
            'PHONE'=>$mobile,				//被叫号码
            'CONTENT'=>$content,				//内容
            );
        $re= $this->postSMS($this->http,$data); //print_r($re);
        if(strpos ($re, "SUCCESS") == 0 )
        {
            return "发送成功!";
        }
        else 
        {
            return "发送失败!";
        }
    }

    public function postSMS($url,$data='')
    {
        $row = parse_url($url);
        $host = $row['host'];
        $port = $row['port'] ? $row['port']:8080;
        $file = $row['path'];
        $post = '';
        while (list($k,$v) = each($data)) 
        {
            $post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
        }
        $post = substr( $post , 0 , -1 );
        $len = strlen($post);
        $fp = fsockopen( $host ,$port, $errno, $errstr, 10);
        if (!$fp) {
            return "$errstr ($errno)\n";
        } else {
            $receive = '';
            $out = "POST $file HTTP/1.1\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Content-Length: $len\r\n\r\n";
            $out .= $post;		
            fwrite($fp, $out);
            while (!feof($fp)) {
                $receive .= fgets($fp, 128);
            }
            fclose($fp);
            $receive = explode("\r\n\r\n",$receive);
            unset($receive[0]);
            return implode("",$receive);
        }
    }
}