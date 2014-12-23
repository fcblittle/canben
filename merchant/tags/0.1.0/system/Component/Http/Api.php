<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Http;

class Api {
    
    public function call($api, $args = array(), $method = 'GET') {
        $path = url($api, array('absolute' => TRUE));
        return Curl::request($path, $args, $method);
    }
}