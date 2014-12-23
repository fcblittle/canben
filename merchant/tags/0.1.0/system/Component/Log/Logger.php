<?php

/**
 * xframework - 敏捷高效的php框架
 *
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Log;

abstract class Logger {

    /**
     * 日志级别
     * @var array
     */
    private $levels = array(
        'EMERGENCY' => 0,
        'ALERT'     => 1,
        'CRITICAL'  => 2,
        'ERROR'     => 3,
        'WARNING'   => 4,
        'NOTICE'    => 5,
        'INFO'      => 6,
        'DEBUG'     => 7,
    );

    /**
     * 选项
     * @var array
     */
    private $options = array();

    /**
     * 设置选项
     * @param array $options
     * @return mixed
     */
    abstract public function setOptions(array $options = array());

    /**
     * 记录日志
     * @param array $data
     * @return mixed
     */
    abstract public function log(array $data = array());

    /**
     * 获取级别代码
     * @param string $name
     * @return mixed
     */
    abstract public function getLevelCode($name = '');
}