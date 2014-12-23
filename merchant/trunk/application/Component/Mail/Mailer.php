<?php
 
/**
 * 邮件发送 
 *
 * @author xlight <i@im87.cn>
 */

namespace Application\Component\Mail;

use System\Component\Mail;

class Mailer {
    
    /**
     * phpmailer实例
     */
    public $mailer = null;
    
    /**
     * 邮件发送参数
     */
    private $params = array();
   
    public function __construct($params = array()) {
       if ($params) {
            $this->setParams($params);
        }

        //邮件系统配置
        $this->mailer = new Mail\PHPMailer;
        $this->mailer->CharSet    = "UTF-8"; // charset
        $this->mailer->Encoding   = "base64";
        $this->mailer->IsSMTP(); // telling the class to use SMTP
        $this->mailer->SMTPAuth   = true;
        $this->mailer->IsHTML(true); // 以 HTML发送 
       
    }
    
    /**
     * 发送邮件
     * 
     * 邮箱格式：
     * array(array('abc@abc.com', 'name'))
     * 
     * @param $to array 要发给的邮箱
     * @param $subject string 邮件标题
     * @param $body string 邮件正文
     * @param $params array 邮件发送选项
     * @return bool
     */
    public function send(array $to, $subject, $body, $params = array()) {
        if ($params) {
            $this->setParams($params);
        }
        $this->mailer->Host       = $this->params['host'];
        $this->mailer->Username   = $this->params['user']; // SMTP account username
        $this->mailer->Password   = $this->params['pass']; // SMTP account password
        $this->mailer->From       = $this->params['from'];
        $this->mailer->FromName   = $this->params['name'];
        $this->mailer->Subject    = $subject;
        $this->mailer->Body       = $body;
        foreach ($to as $v) {
            $this->mailer->AddAddress($v[0], $v[1] ?: '');
        }
        return $this->mailer->Send();
    }
    
    public function getErrorInfo() {
        return $this->mailer->ErrorInfo;
    }
    
    public function setParams(array $params) {
        $this->params = array_merge($this->params, $params);
    }
}