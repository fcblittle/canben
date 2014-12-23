<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\View\Helper;

use System\Bootstrap;

/**
 * View helper: Form
 * 
 * @author xlight <i@im87.cn>
 */
class Form {
    
    public function __construct() {
        $this->thread = Bootstrap::getActiveThread();
    }
    
    /**
     * Generate a unique token.
     * 
     * @param string $name token name
     * @return string
     */
    public function getToken($name = '') {
        $name = $this->thread->getPath() . '.' . $name;
        if (! isset($_SESSION['token'])) {
            $_SESSION['token'] = array();
        }
        $_SESSION['token'][$name] = md5($name . '.' . uniqid('token', TRUE));
        
        return $_SESSION['token'][$name];
    }
    
    /**
     * Get token value via name.
     * 
     * @param string $token
     * @param string $name
     * @return bool
     */
    public function validToken($token, $name = '') {
        $name = $this->thread->getPath() . '.' . $name;
        if (! isset($_SESSION['token'])) {
            return FALSE;
        }
        return $token === $_SESSION['token'][$name];
    }

}
