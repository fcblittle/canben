<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System;

/**
 * 异常（Exception）基类
 * 
 * @author xlight <i@im87.cn>
 */
class Exception extends \Exception {
    
    /**
     * Previous exception
     * @var null|Exception
     */
    private $_previous = NULL;
    
    /**
     * Exception message.
     * @var string
     */
    protected $message  = '';
    
    /**
     * Exception code.
     * @var int
     */
    private $_code = 0;

    /**
     * Construct the exception
     *
     * @param  string $msg
     * @param  int $code
     * @param  Exception $previous
     * @return void
     */
    public function __construct($msg = '', $code = 0, $previous = NULL) {
        $this->_previous = $previous;
        parent::__construct($msg, (int) $code, $previous);
        $this->_code = $code;
        $this->message = $msg;
    }

    /**
     * Overloading
     *
     * For PHP < 5.3.0, provides access to the getPrevious() method.
     *
     * @param  string $method
     * @param  array $args
     * @return mixed
     */
    public function __call($method, array $args) {
        if ('getprevious' == strtolower($method)) {
            return $this->_getPrevious();
        }
        return NULL;
    }

    /**
     * String representation of the exception
     *
     * @return string
     */
    public function __toString() {
        parent::__toString();
        return $this->message;
    }
    
    /**
     * Returns previous Exception
     *
     * @return Exception|null
     */
    protected function _getPrevious() {
        return $this->_previous;
    }
}
