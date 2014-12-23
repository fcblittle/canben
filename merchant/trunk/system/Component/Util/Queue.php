<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Util;

class Queue {
    
    private static $queue = array();
    
    /**
     * Setup a queue
     * 
     * @param string $name
     * @param mixed $value
     */
    public static function set($name, $value = array()) {
        if (! isset(self::$queue[$name])) {
            self::$queue[$name] = is_array($value) ? $value : array($value);
        } else {
            if (is_array($value)) {
                self::$queue[$name] = array_merge(self::$queue[$name], $value);
            } else {
                self::$queue[$name][] = $value;
            }
        }
    }
    
    /**
     * Get value of one queue
     * 
     * @param string $name
     * @return array
     */
    public static function get($name) {
        return self::$queue[$name] ? array_unique(self::$queue[$name]) : array();
    }
    
    /**
     * 获取所有队列
     */
    public static function & getAll() {
        foreach (self::$queue as $k => $queue) {
            self::$queue[$k] = array_unique($queue);
        }
        return self::$queue;
    }
    
    /**
     * 清空队列
     * 
     * @param string $name 要清空的队列名，不填则清空所有
     * @return void
     */
    public static function clear($name = '') {
        if ($name) {
            unset(self::$queue[$name]);
        } else {
            self::$queue = array();
        }
    }
}
