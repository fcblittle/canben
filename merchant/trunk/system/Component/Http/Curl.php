<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Http;

class Curl {
    /**
     * 发送请求
     */
    public static function request($url, $query = null, $method = 'GET') {
        $ch = curl_init();  
        if ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        } else {
            if (is_array($query)) {
                $query = self::buildQuery($query);
            }
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);  
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);  
        $data = curl_exec($ch);
        curl_close($ch);  

        return $data;
    }

    public static function get($url, $query = null) {
        return self::request($url, $query);
    }
    
    public static function post($url, $query = array()) {
        return self::request($url, $query, 'POST');
    }
    
    /**
     * 组合参数
     */
    public static function buildQuery(array $query, $encode = FALSE) {
        if (empty($query)) {
            return '';
        }
        foreach ($query as $k => $v) {
                $q[] = $k . '=' . ($encode ? urlencode($v) : $v);
            }
        return implode('&', $q);
    }
}
