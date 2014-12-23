<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Controller;

use System\Controller,
    System\Loader;

class Error extends Controller {
    
    public function render() {
        Loader::load('System\Resource\views\error', '.tpl.php');
    }
    
}