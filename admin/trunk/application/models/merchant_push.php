<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 百度云推送模型---商户端
 *
 * @author 再晨
 * @package 
 */
class Merchant_push extends CI_Model {
    private $apiKey_merchant = "FSoKX5TY6WXumP2RDIokquYX";
    private $secretKey_merchant = "mRabms2edbDiqDG6Q3xLdZnhONSL8kDk";
    function __construct(){
         parent::__construct();
         $config = array('apiKey' => $this->apiKey_merchant,'secretKey' => $this->secretKey_merchant );
         $this->load->library('Channel',$config);
    }
    //安卓端推送 
    public function pushMessage_android($push_type,$tag_name=null,$user_id=null,$channel_id=null,$message,$message_key){
    	switch ($push_type) {
    		case 1:
			    $optional[Channel::USER_ID] = $user_id;
			    $optional[Channel::CHANNEL_ID] = $channel_id;
			    //$message = $message;
			    $message_key = "msg_key";
			    $ret = $this->channel->pushMessage ( $push_type, $message, $message_key, $optional );
			    
			    //推送通知，必须指定MESSAGE_TYPE为1
			    //$optional[Channel::MESSAGE_TYPE] = 1;
			    //通知必须按以下格式指定
			    /*$message = '{ 
				"title": "'.$messages['title'].'",
				"description": "'.$messages['description'].'"
			    }';*/
			    //$message_key = "msg_key";
			    //$ret = $this->channel->pushMessage ( $push_type, $message, $message_key, $optional );
    			break;
    		case 2:
			    $tag_name = $tag_name;
			    $optional[Channel::TAG_NAME] = $tag_name;
			    $ret = $this->channel->pushMessage($push_type, $message, $message_key, $optional);
    			break;
    		case 3:
    			$ret = $this->channel->pushMessage($push_type, $message, $message_key);
    			break;    		
    		default:
    			$ret = $this->channel->pushMessage($push_type, $message, $message_key);
    			break;
    	}
	    //$channel = new Channel ($apiKey, $secretKey) ;		
	    //推送单播消息，必须指定user_id或者user_id+channel_id
	    ////$push_type = 1;
	    //$user_id = "844160664764854875";
	    //$channel_id = "4357183204576226035";
	    //$user_id = "837005011407972731";
	    //$channel_id = "3911466209896192233";
	    
	    ////$optional[Channel::USER_ID] = $user_id;
	    ////$optional[Channel::CHANNEL_ID] = $channel_id;
	    ////$message = "Hello World你好";
	    ////$message_key = "msg_key";
	    ////$ret = $this->channel->pushMessage ( $push_type, $message, $message_key, $optional );
	    
	    //推送通知，必须指定MESSAGE_TYPE为1
	    ////$optional[Channel::MESSAGE_TYPE] = 1;
	    //通知必须按以下格式指定
	    /*$message = '{ 
		"title": "'.$messages['title'].'",
		"description": "'.$messages['description'].'"
	    }';*/
	    ////$message_key = "msg_key";
	    ////$ret = $this->channel->pushMessage ( $push_type, $message, $message_key, $optional );

	    //推送消息到一群人，按tag推送,必须指定tag_name
	    //$push_type = 2;
	    //$tag_name = ‘xxx’;
	    //$optional[Channel::TAG_NAME] = $tag_name;
	    //$ret = $channel->pushMessage($push_type, $messages, $message_keys, $optional);

	    //推送消息到某个应用下的所有人，不用指定user_id, channel_id, tag_name
	    //$push_type = 3;
	    //$ret = $channel->pushMessage($push_type, $messages, $message_keys);
	    
	    //检查返回值
	    if ( false === $ret ){
	        //echo ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!\n' );
	        $WRONG = ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!\n' );
	        //echo ( 'ERROR NUMBER: ' . $this->channel->errno ( ) . '\n' );
	        $ERROR_NUMBER = ( 'ERROR NUMBER: ' . $this->channel->errno ( ) . '\n' );
	        //echo ( 'ERROR MESSAGE: ' . $this->channel->errmsg ( ) . '\n' );
	        $ERROR_MESSAGE = ( 'ERROR MESSAGE: ' . $this->channel->errmsg ( ) . '\n' );
	        //echo ( 'REQUEST ID: ' . $this->channel->getRequestId ( ) . '\n' );
	        $REQUEST_ID = ( 'REQUEST ID: ' . $this->channel->getRequestId ( ) . '\n' );
$resp = $WRONG . "<br/>".$ERROR_NUMBER."<br/>".$ERROR_MESSAGE."<br/>".$REQUEST_ID;
$file3 = fopen('./push_test_android_err.txt', 'a+');
fwrite($file3,$resp."\n\r");
fclose($file3);
unset($file3);
	        return false;
	    }else{
	        //echo ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!'. '\n' );
	        $a =  ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!'. '\n' );
	        //echo ( 'result: ' . print_r ( $ret, true ) . '\n' );
	        $b = ( 'result: ' . print_r ( $ret, true ) . '\n' );
$resp = $a."<br/>".$b;	        
$file2 = fopen('./push_test_android_succ.txt', 'a+');
fwrite($file2,$resp."\n\r");
fclose($file2);
unset($file2);
	        return $ret;
	    }
    }
	//推送ios设备消息
	function pushMessage_ios ($user_id,$channel_id,$message,$message_key){
	    $push_type = 1; //推送单播消息
	    $optional[Channel::USER_ID] = $user_id; //如果推送单播消息，需要指定user
	    //指定发到ios设备
	    $optional[Channel::DEVICE_TYPE] = 4;
	    //指定消息类型为通知
	    $optional[Channel::MESSAGE_TYPE] = 1;
	    //如果ios应用当前部署状态为开发状态，指定DEPLOY_STATUS为1，默认是生产状态，值为2.
	    //旧版本曾采用不同的域名区分部署状态，仍然支持。
	    $optional[Channel::DEPLOY_STATUS] = 1;
	    //通知类型的内容必须按指定内容发送，示例如下：
	    $message = '{ 
	        "aps":{
	            "alert":"'.$message.'",
	            "sound":"",
	            "badge":0
	        }
	    }';	    
	    $message_key = "msg_key";
	    $ret = $this->channel->pushMessage ( $push_type, $message, $message_key, $optional ) ;
	    if ( false === $ret )
	    {
	        //error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!' ) ;
	        $WRONG = ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!\n' );
	        //error_output ( 'ERROR NUMBER: ' . $this->channel->errno ( ) ) ;
	        $ERROR_NUMBER = ( 'ERROR NUMBER: ' . $this->channel->errno ( ) . '\n' );
	        //error_output ( 'ERROR MESSAGE: ' . $this->channel->errmsg ( ) ) ;
	        $ERROR_MESSAGE = ( 'ERROR MESSAGE: ' . $this->channel->errmsg ( ) . '\n' );
	        //error_output ( 'REQUEST ID: ' . $this->channel->getRequestId ( ) );
	        $REQUEST_ID = ( 'REQUEST ID: ' . $this->channel->getRequestId ( ) . '\n' );
$resp = $WRONG . "<br/>".$ERROR_NUMBER."<br/>".$ERROR_MESSAGE."<br/>".$REQUEST_ID;
$file6 = fopen('./push_test_ios_err.txt', 'a+');
fwrite($file6,$resp."\n\r");
fclose($file6);
unset($file6);
	    }
	    else
	    {
	        //right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
	        $a =  ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!'. '\n' );
	        //right_output ( 'result: ' . print_r ( $ret, true ) ) ;
	        $b = ( 'result: ' . print_r ( $ret, true ) . '\n' );
$resp = $a."<br/>".$b;	        
$file7 = fopen('./push_test_ios_succ.txt', 'a+');
fwrite($file7,$resp."\n\r");
fclose($file7);
unset($file7);
	    }
	}
	//
	function push_setTag($tag_name, $user_id){
	    //$channel = new Channel($apiKey, $secretKey);
	    $optional[Channel::USER_ID] = $user_id;
	    $ret = $this->channel->setTag($tag_name, $optional);
	    if (false === $ret) {   
	        //error_output('WRONG, ' . __FUNCTION__ . ' ERROR!!!!!');
	        $WRONG = ('WRONG, ' . __FUNCTION__ . ' ERROR!!!!!');
	        //error_output( 'ERROR NUMBER: ' . $this->channel->errno());
	        $ERROR_NUMBER = ( 'ERROR NUMBER: ' . $this->channel->errno ( ) . '\n' );
	        //error_output('ERROR MESSAGE: ' . $this->channel->errmsg());
	        $ERROR_MESSAGE = ( 'ERROR MESSAGE: ' . $this->channel->errmsg ( ) . '\n' );
	        //error_output('REQUEST ID: ' . $this->channel->getRequestId());
	        $REQUEST_ID = ( 'REQUEST ID: ' . $this->channel->getRequestId ( ) . '\n' );
$resp = $WRONG . "<br/>".$ERROR_NUMBER."<br/>".$ERROR_MESSAGE."<br/>".$REQUEST_ID;
$file4 = fopen('./push_test_settag_err.txt', 'a+');
fwrite($file4,$resp."\n\r");
fclose($file4);
unset($file4);
	        return false;
	    } else {   
	        //echo ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!'. '\n' );
	        $a =  ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!'. '\n' );
	        //echo ( 'result: ' . print_r ( $ret, true ) . '\n' );
	        $b = ( 'result: ' . print_r ( $ret, true ) . '\n' );
$resp = $a."<br/>".$b;	
$file5 = fopen('./push_test_settag_succ.txt', 'a+');
fwrite($file5,$resp."\n\r");
fclose($file5);
unset($file5);
	        return true;
	    }
	}
}