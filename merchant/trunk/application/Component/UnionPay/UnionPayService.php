<?php

namespace Application\Component\UnionPay;

class UnionPayService
{
    const RESP_SUCCESS  = "00"; //返回成功

    const QUERY_SUCCESS = "0";  //查询成功
    const QUERY_FAIL    = "1";
    const QUERY_WAIT    = "2";
    const QUERY_INVALID = "3";

    var $args;
    var $api_url;

    var $signature;
    var $signMethod;

    public function __construct($args, $service)
    {
        $this->UnionPay($args, $service);
    }

    public function UnionPay($args, $service)
    {
        $param_check    = array();
        switch($service)
        {
            case UnionPayConfig::FRONT_PAY:
                $trans_type = $args['transType'];
                if ($trans_type != UnionPayConfig::CONSUME && $trans_type != UnionPayConfig::PRE_AUTH) {
                    //前台交易仅支持 消费 和 预授权
                    throw new \Exception("Bad trans_type for front_pay. Use back_pay instead");
                }
                $this->api_url = UnionPayConfig::$front_pay_url;
                $this->args = array_merge(UnionPayConfig::$pay_params_empty,
                                        UnionPayConfig::$pay_params, $args);
                $param_check = UnionPayConfig::$pay_params_check;
                break;

            case UnionPayConfig::BACK_PAY:
                $this->api_url = UnionPayConfig::$back_pay_url;
                $this->args = array_merge(UnionPayConfig::$pay_params_empty,
                                        UnionPayConfig::$pay_params, $args);
                $param_check = UnionPayConfig::$pay_params_check;
                $trans_type = $this->args['transType'];
                if ($trans_type == UnionPayConfig::CONSUME || $trans_type == UnionPayConfig::PRE_AUTH) {
                    if (!isset($this->args['cardNumber']) && !isset($this->args['pan'])) {
                        throw new \Exception('consume OR pre_auth transactions need cardNumber!');
                    }
                }
                else {
                    if (empty($this->args['origQid'])) {
                        throw new \Exception('origQid is not provided');
                    }
                }
                break;

            case UnionPayConfig::QUERY:
                $this->api_url = UnionPayConfig::$query_url;
                $args['version']    = UnionPayConfig::$pay_params['version'];
                $args['charset']    = UnionPayConfig::$pay_params['charset'];
                $args['merId']      = UnionPayConfig::$pay_params['merId'];

                if (empty(UnionPayConfig::$pay_params['merId']) &&
                    empty(UnionPayConfig::$pay_params['acqCode']))
                {
                    throw new \Exception('merId and acqCode can\'t be both empty');
                }

                //acqCode在QUERY请求中作为保留域存在
                if (!empty(UnionPayConfig::$pay_params['acqCode'])) {
                    $acqCode = UnionPayConfig::$pay_params['acqCode'];
                    $args['merReserved'] = "{acqCode=$acqCode}";
                }
                else {
                    $args['merReserved'] = '';
                }

                $this->args = $args;
                $param_check = UnionPayConfig::$query_params_check;
                break;

            case UnionPayConfig::RESPONSE:
                $arr_args = array();
                $arr_reserved = array();
                if (is_array($args)) {
                    $arr_args       = $args;
                    $cupReserved    = isset($arr_args['cupReserved']) ? $arr_args['cupReserved'] : '';
                    parse_str(substr($cupReserved, 1, -1), $arr_reserved); //去掉前后的{}
                }
                else {
                    $cupReserved = '';
                    $pattern = '/cupReserved=(\{.*?\})/';
                    if (preg_match($pattern, $args, $match)) { //先提取cupReserved
                        $cupReserved = $match[1];
                    }
                    //将cupReserved的value清除(因为含有&, parse_str没法正常处理)
                    $args_r         = preg_replace($pattern, 'cupReserved=', $args);
                    parse_str($args_r, $arr_args);
                    $arr_args['cupReserved'] = $cupReserved;
                    parse_str(substr($cupReserved, 1, -1), $arr_reserved); //去掉前后的{}
                }

                //提取服务器端的签名
                if (!isset($arr_args['signature']) || !isset($arr_args['signMethod'])) {
                    throw new \Exception('No signature Or signMethod set in notify data!');
                }
                $this->signature = $arr_args['signature'];
                $this->signMethod= $arr_args['signMethod'];
                unset($arr_args['signature']);
                unset($arr_args['signMethod']);

                //验证签名
                $signature = self::sign($arr_args, $this->signMethod);
                if ($signature != $this->signature) {
                    throw new \Exception('Bad signature returned!');
                }

                $this->args = array_merge($arr_args, $arr_reserved);
                unset($this->args['cupReserved']);

                return; //RESPONSE参数不需要作后续处理了

            default:
                throw new \Exception("Unknown service provided.");
        }

        if (isset($this->args['commodityUrl'])) {
            $this->args['commodityUrl'] = self::encodeURI($this->args['commodityUrl']);
        }

        //merReserved: 前后台支付、查询
        $has_reserved = false;
        $arr_reserved = array();
        foreach (UnionPayConfig::$mer_params_reserved as $key) {
            if (isset($this->args[$key])) {
                $value = $this->args[$key];
                unset($this->args[$key]);
                $arr_reserved[] = "$key=$value";
                $has_reserved = true;
            }
        }
        if ($has_reserved) {
            $this->args['merReserved'] = sprintf("{%s}", join("&", $arr_reserved));
        }
        else {
            //请求一定有merReserved字段
            if (!isset($this->args['merReserved'])) {
                $this->args['merReserved'] = '';
            }
        }

        //param check
        foreach ($param_check as $key) {
            if (!isset($this->args[$key])) {
                throw new \Exception("KEY [$key] not set in params given");
            }
        }

        //signature
        $this->args['signature']    = self::sign($this->args, UnionPayConfig::$sign_method);
        $this->args['signMethod']   = UnionPayConfig::$sign_method;
    }

    function get($key, $default = null)
    {
        if (isset($this->args[$key])) {
            return $this->args[$key];
        }
        return $default;
    }

    function get_args()
    {
        return $this->args;
    }

    static function encode($value, $output_charset, $input_charset)
    {
        if (strtolower($input_charset) == strtolower($output_charset) || empty($value)) {
            return $value;
        }
        if (function_exists("iconv")) {
            return iconv($input_charset, $output_charset, $value);
        }
        if (function_exists("mb_convert_encoding")) {
            return mb_convert_encoding($value, $output_charset, $input_charset);
        }
        throw new \Exception("sorry, mbstring or iconv is needed for charset conversion.");
    }

    static function make_seed()
    {
        list($usec, $sec) = explode(' ', microtime());
        return (float) $sec + ((float) $usec * 100000);
    }

    static function encodeURI($url)
    {
        if (preg_match("/^(.*?)\?(.*)/", $url, $match)) {
            $prefix = preg_replace("/\?.*/", "", $url);
            $query_string = $match[2];
            $arr_keqv = explode('&', $query_string);
            $arr_encoded = array();
            foreach ($arr_keqv as $keqv) {
                list($key, $value) = explode('=', $keqv);
                $arr_encoded[] = sprintf("%s=%s", $key, urlencode($value));
            }
            $query_string = join('&', $arr_encoded);
            return $prefix . '?' . $query_string;
        }
        else {
            return $url;
        }
    }

    static function decodeURI($url)
    {
        if (preg_match("/^(.*?)\?(.*)/", $url, $match)) {
            $prefix = preg_replace("/\?.*/", "", $url);
            $query_string = $match[2];
            $arr_keqv = explode('&', $query_string);
            $arr_decoded = array();
            foreach ($arr_keqv as $keqv) {
                list($key, $value) = explode('=', $keqv);
                $arr_decoded[] = sprintf("%s=%s", $key, urldecode($value));
            }
            $query_string = join('&', $arr_decoded);
            return $prefix . '?' . $query_string;
        }
        else {
            return $url;
        }
    }

    static function sign($params, $sign_method)
    {
        if (strtolower($sign_method) == "md5") {
            ksort($params);
            $sign_str = "";
            foreach ($params as $key => $val) {
                if (in_array($key, UnionPayConfig::$sign_ignore_params)) {
                    continue;
                }
                $sign_str .= sprintf("%s=%s&", $key, $val);
            }
            return md5($sign_str . md5(UnionPayConfig::$security_key));
        }
        /* TODO: elseif (strtolower($sign_method) == "rsa")  */
        else {
            throw new \Exception("Unknown sign_method set in UnionPayConfig");
        }
    }

    function create_html()
    {
        $html = "<form id=\"pay_form\" target=\"_blank\" name=\"pay_form\" action=\"{$this->api_url}\" method=\"post\">";
        foreach ($this->args as $key => $value) {
            $html .= "<input type=\"hidden\" name=\"{$key}\" id=\"{$key}\" value=\"{$value}\" />\n";
        }
        $html .= '<input type="submit" type="hidden"></form>';

        return $html;
    }

    function create_query_string()
    {
        $query_string = '';
        foreach ($this->args as $key => $value) {
            $query_string .= sprintf("%s=%s&", $key, urlencode($value));
        }
        return $query_string;
    }

    function post() 
    {
        return self::curl_call($this->api_url, $this->args, true, array(
                        CURLOPT_SSL_VERIFYPEER  => UnionPayConfig::VERIFY_HTTPS_CERT,
                        CURLOPT_SSL_VERIFYHOST  => UnionPayConfig::VERIFY_HTTPS_CERT,
                    ));
    }

    /*
     * curl_call
     *
     * @url:  string, curl url to call, may have query string like ?a=b
     * @data: array(key => value), data for get/post
     * @is_post, boolean, true=post, false=get
     * @options, array(curl_option => option_value), extra curl options
     *
     * return param:
     *  mixed:
     *    false: error happened
     *    string: curl return data
     * 
     */
    static function curl_call($url, $data = null, $is_post = true, $options = null) 
    {
        if (function_exists("curl_init")) {
            $curl = curl_init();

            if (is_array($data)) {
                $data = http_build_query($data);
            }

            if ($is_post) { 
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
            else { //GET
                if (!empty($data)) { 
                    $sep = '?';
                    if (strpos($url, '?') !== false) { 
                        $sep = '&';
                    }
                    $url .= $sep . $data;
                }
            }

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_TIMEOUT, 60); //seconds

            if (is_array($options)) { 
                foreach($options as $key => $value) {
                    curl_setopt($curl, $key, $value);
                }
            }

            $ret_data = curl_exec($curl);

            if (curl_errno($curl)) {
                printf("curl call error(%s): %s\n", curl_errno($curl), curl_error($curl));
                curl_close($curl);
                return false;
            }
            else {
                //printf("curl call ok, ret_data: %s\n", $ret_data);
                curl_close($curl);
                return $ret_data;
            }
        }
        /* TODO: elseif (function_exists('fsockopen')) { } */
        else {
            throw new \Exception("[PHP] curl module is required");
        }
    }
}