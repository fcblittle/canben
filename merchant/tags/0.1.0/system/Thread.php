<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System;

use System\Loader;
use System\Component\Http;

/**
 * 线程（Thread）
 * 
 * 实现程序的单实例“多开”
 * 
 * @author xlight <i@im87.cn>
 */
class Thread {
    
    /**
     * 线程路径
     * @var string
     */
    private $path = '';
    
    /**
     * 参数
     * @var array
     */
    private $args = array();
    
    /**
     * 配置
     * @var array
     */
    private $config = array();
    
    /**
     * 模块列表
     * @var array
     */
    private $modules = array();
    
    /**
     * 当前模块
     * @var string
     */
    private $module = '';
    
    /**
     * 当前控制器名称
     * @var string
     */
    private $controllerName = '';
    
    /**
     * 当前操作
     * @var string
     */
    private $action = '';
    
    /**
     * __construct()
     */
    public function __construct($path = '', $args = array()) {
        $path && $this->path = $path;
        $args && $this->args = $args;
        $this->config = Bootstrap::getConfig();
        $this->modules = Bootstrap::getModules();
        $this->routing();
        $this->initModule();
        $this->request = new Http\Request($path);
        $this->response = new Http\Response;
        Bootstrap::invokeHook('onThreadInit', $this);
    }
    
    /**
     * 设置请求路径
     * 
     * @param string $path 内部路径
     */
    public function setPath($path) {
        $this->path = $path;
    }
    
    /**
     * 获取请求路径
     * 
     * @return string 内部路径
     */
    public function getPath() {
        return $this->path;
    }
    
    /**
     * 为当前路径做路由处理
     * 
     * 解析当前url，并查找是否存在定义的url别名
     */
    public function routing() {
        $path = $this->getPath();
        $rules = Bootstrap::getRoutingRules();
        if ($rules !== false && is_array($rules)) {
            // Find alias.
            foreach ($rules as $alias => $target) {
                // alias exists.
                if (preg_match('#^' . $alias . '$#i', $path)) {
                    // back-reference
                    if (strstr($target, '$') && strstr($alias, '(')) {
                        $path = preg_replace('#^' . $alias . '$#i', $target, $path);
                    } else {
                        $path = $target;
                    }
                    $this->setPath($path);
                    break;
                } 
            }
        }
    }
    
    /**
     * 初始化模块
     */
    public function initModule() {
        $module = $this->getModule();
        if ($module !== '') {
            $config = Loader::load("Module\{$module}\Config\config");
            if ($config) {
                $this->config = merge_options($this->config, $config);
            }
            // 调用钩子: onModuleInit().
            Bootstrap::invokeHook('onModuleInit');
        }
        return $this;
    }
    
    /**
     * 获取当前模块
     * 
     * @return string 当前模块名
     */
    public function getModule() {
        if ($this->module === '') {
            if (empty($this->modules)) {
                return '';
            }
            $module = '';
            foreach ($this->modules as $v) {
                $tmp = str_replace('\\', '/', $v);
                $m = preg_match('#^' . $tmp . '/?#i', $this->path);
                if ($m === 1) {
                    $module = $v;
                    break;
                }
            }
            if (! $module && $this->config['common']['defaultModule']) {
                $module = $this->config['common']['defaultModule'];
                $this->setPath($module . '/' . $this->getPath());
            }

            $module = Bootstrap::formatPath($module);
            $path = MODULE_ROOT . SEP . $module;
            if (! file_exists($path)) {
                throw new Exception(t('Cannot find module: @name', 
                array('@name' => $module)));
            }
            $this->module = $module;
        }

        return $this->module;
    }
    
    /**
     * 获取控制器实例
     * 
     * @param array $args 传给控制器的参数
     * @return object 控制器实例
     * @throws Exception 404
     */
    public function getController($args = array()) {
        if ($this->controllerName === '') {
            $controller = $ns = '';
            $module = str_replace('\\', '/', $this->getModule());
            $path = preg_replace('#^(' . $module . '/?)#i', '', $this->path);
            $prefix = $module ? "Module\\{$module}" : 'Application';
            $parts = explode('/', $path);
            for ($i = 0; $i < count($parts); $i++) {
                $parts[$i] = ucfirst($parts[$i]);
                $part = implode('\\', array_slice($parts, 0, $i + 1));
                $ns = "{$prefix}\\Controller\\{$part}";
                if (Loader::load($ns) !== false) {
                    $controller = $part;
                    break;
                }
            }
            if (! $controller) {
                $controller = ucfirst($this->config['common']['defaultController']);
                $ns = "{$prefix}\\Controller\\{$controller}";
                if (Loader::load($ns) === false) {
                    throw new Exception(t('Controller file \'@ns\' cannot be found.', 
                        array('@ns' => $ns)), 404);
                }
            }
            if (class_exists($ns)) {
                $this->controllerName = $controller;
                $this->controllerNS = $ns;
                $rf = new \ReflectionClass($ns);
                return $rf->newInstanceArgs($args);
            } else {
                throw new Exception(t('Controller class \'@ns\' cannot be found.',
                        array('@ns' => $ns)), 404);
            }
        }
    }
    
    /**
     * 获取控制器名称
     */
    public function getControllerName() {
        return $this->controllerName;
    }
    
    /**
     * 获取当前操作
     * 
     * @return string 当前操作名
     */
    public function getAction() {
        if ($this->action === '') {
            $module = str_replace('\\', '/', $this->getModule());
            $controllerName = str_replace('\\', '/', $this->controllerName);
            $path = preg_replace(
                '#^(' . $module . '/?' . $controllerName . '/?)#i', '',
                $this->path, -1, $count);
            $count === 0 && $path = '';
            $parts = explode('/', $path);
            $this->action = ! empty($parts[0])
                ? $parts[0] 
                : $this->config['common']['defaultAction'];
        }

        return $this->action;
    }
    
    /**
     * 运行线程
     * 
     * @param array $args 运行参数
     * @return mixed 运行结果
     */
    public function run($args = array()) {
        $args = merge_options($this->args, $args);
        $controller = $this->getController(array($args));
        $action     = $this->getAction();
        if (method_exists($controller, $action) ||
            method_exists($controller, '__call')) {
            return call_user_func_array(array($controller, $action), array($args));
        }
        throw new Exception(
            t('Action \'@action\' cannot be found.', 
            array('@action' => $action)), 
        404);
    }
}