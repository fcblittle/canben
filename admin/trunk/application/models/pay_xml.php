<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 网银支付XML模型
 *
 * @author 再晨
 * @package Pay_xml
 */
class Pay_xml extends CI_Model {
    function __construct(){
         parent::__construct();
    }
    //
	function parse($xml){
		return simplexml_load_string($xml);
	}
	//
	function create($version,$merchant,$terminal,$data,$sign){
		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><chinabank/>');
		$xml->addChild('version',$version);
		$xml->addChild('merchant',$merchant);
		$xml->addChild('terminal',$terminal);
		$xml->addChild('data',$data);
		$xml->addChild('sign',$sign);
		return $xml->asXML();
	}

}