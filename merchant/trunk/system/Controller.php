<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System;

use System\Bootstrap;
use System\Thread;
use System\Component\Http\Request;

/**
 * 控制器（Controller）基类
 * 
 * 所有控制器（Controller）必须扩展此基类才能正常使用
 * 
 * @author xlight <i@im87.cn>
 */
class Controller {
    
    /**
     * 活动线程
     * @var Thread
     */
    public $thread = null;
    
    /**
     * __construct()
     */
    public function __construct() {
        $this->thread = Bootstrap::getActiveThread();
        $this->config = Bootstrap::getConfig();
        $this->request = $this->thread->request;
        $this->response = $this->thread->response;
        // 全局变量
        foreach (Bootstrap::getGlobal() as $key => $value) {
            $this->{$key} = $value;
        }
        
        // 调用钩子: onControllerInit()
        Bootstrap::invokeHook('onControllerInit', $this);
    }
    
    /**
     * 快捷调用路径
     * 
     * @param string $path 要调用的路径
     * @param array $args 参数
     * @return mixed
     */
    public function call($path, $args = array()) {
        return Bootstrap::call($path, $args);
    }
    
    /**
     * 内部跳转
     * 
     * @param string $path 要调用的路径
     * @param array $args 参数
     * @return mixed
     */
    public function forward($path, $args = array()) {
        return Bootstrap::forward($path);
    }
    
    /**
     * 快捷实例化模型（Model）
     * 
     * @param string $name 模型名
     * @param bool $reset 是否重新实例化
     * @return object 模型实例
     */
    public function model($path, $reset = false) {
        if (substr($path, 0, 1) === ':') {
            $module = $this->thread->getModule();
            $path = "Module\\{$module}" . $path;
        }
        
        return Bootstrap::model($path, $reset);
    }
    
    /**
     * 快捷实例化组件（Component）
     * 
     * @param string $name 组件名
     * @param array $params 组件实例化的参数
     * @param bool $reset 是否重新实例化
     * @return object 组件实例
     */
    public function com($path, $params = array(), $reset = false) {
        if (substr($path, 0, 1) === ':') {
            $module = $this->thread->getModule();
            $path = "Module\\{$module}" . $path;
        }
        
        return Bootstrap::com($path, $params, $reset);
    }
    
    /**
     * __destruct()
     */
    public function __destruct() {
        // Invoke hook: onControllerDestruct()
        Bootstrap::invokeHook('onControllerDestruct', $this);
    }
}