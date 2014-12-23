<?php

/**
 * xframework - 敏捷高效的php框架
 *
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System;

use System\Exception;
use System\Loader;
use System\Component\Locale;

/**
 * Shortcut of DIRECTORY_SEPARATOR.
 */
! defined('SEP') && define('SEP', DIRECTORY_SEPARATOR);

/**
 * Start memory usage.
 */
define('MEMORY_USAGE', memory_get_usage());

/**
 * Unique request timestamp.
 */
define('REQUEST_TIME', (int) $_SERVER['REQUEST_TIME']);

/**
 * 记录开始时间
 */
define('START_TIME', Bootstrap::timer());

// Include loader.
require 'Loader.php';

/**
 * 程序引导类
 *
 * 初始化并运行程序
 *
 * @author xlight <i@im87.cn>
 */
class Bootstrap {

    /**
     * 框架版本
     */
    const VERSION = '2.3';

    /**
     * 引导(Bootstrap)类实例
     * @var object
     */
    private static $instance   = NULL;

    /**
     * 全局配置
     * @var array
     */
    private static $config     = array();

    /**
     * 全局变量
     * @var array
     */
    private static $global     = array();

    /**
     * 所有模块
     * @var array
     */
    private static $modules    = array();

    /**
     * 所有钩子
     * @var array
     */
    private static $hooks      = array();

    /**
     * 实例化的模型
     * @var array
     */
    private static $models     = array();

    /**
     * 实例化的组件
     * @var array
     */
    private static $coms       = array();

    /**
     * 程序主线程
     * @var Thread
     */
    private static $app        = null;

    /**
     * 当前线程
     * @var Thread
     */
    private static $thread     = null;

    /**
     * 路径别名
     * @var array
     */
    private static $routingRules = array();

    /**
     * 获取引导(Bootstrap)类实例
     *
     * @return Bootstrap 引导(Bootstrap)类实例
     */
    public static function getInstance() {
        if (self::$instance === NULL) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * 获取当前时间（精确到微秒）
     * @return float 微秒时间
     */
    public static function timer() {
        list($usec, $sec) = explode(' ', microtime());

        return ((float)$usec + (float)$sec);
    }

    /**
     * 获取当前系统信息
     *
     * 返回信息包含三个:
     * <li>memoryUsage - 当前脚本内存使用</li>
     * <li>time - 当前脚本执行时间（精确到微秒）</li>
     * <li>includedFiles - 当前已加载的文件列表</li>
     *
     * @return array
     */
    public static function getSystemInfo() {
        return array(
            'memoryUsage'   => memory_get_usage() - MEMORY_USAGE,
            'time'          => self::timer() - START_TIME,
            'includedFiles' => get_included_files()
        );
    }

    /**
     * 初始化程序
     *
     * @return Bootstrap 引导(Bootstrap)类实例
     */
    public function init() {

        Loader::registerNamespace('System', SYS_ROOT);

        ini_set('error_reporting', E_ALL ^ E_NOTICE);

        date_default_timezone_set('UTC');

        // 注册自动加载
        spl_autoload_register(array('System\Loader', 'autoload'));

        // 设置错误处理
        set_error_handler(array($this, 'errorHandler'));

        // 设置异常处理
        set_exception_handler(array($this, 'exceptionHandler'));

        // 程序命名空间
        Loader::registerNamespace('Application', APP_ROOT);

        if (file_exists(MODULE_ROOT)) {
            Loader::registerNamespace('Module', MODULE_ROOT);
        }

        // 获取程序级钩子
        $this->getApplicationHooks();

        // 初始化系统
        $this->initSystem();

        // 初始化程序
        $this->initApplication();

        // 获取模块级钩子
        $this->getModuleHooks();

        return $this;
    }

    /**
     * 初始化系统
     */
    private function initSystem() {

        // 调用钩子: beforeSystemInit()
        self::invokeHook('beforeSystemInit', $this);

        // 加载系统函数库
        Loader::load('System\Resource\functions\function');

        // 调用钩子: onSystemInit()
        self::invokeHook('onSystemInit', $this);
    }

    /**
     * 初始化程序
     */
    private function initApplication() {

        // 加载程序级配置
        $config = Loader::load('Application\Config\config');
        if ($config) {
            self::$config = $config ?: array();
        }

        // 初始化本地化
        if (isset(self::$config['Locale'])) {
            Locale\Locale::setOptions(self::$config['Locale']);
        }

        // 初始化会话
        self::initSession();

        // 调用钩子: onApplicationInit().
        self::invokeHook('onApplicationInit', $this);
    }

    /**
     * 获取实例钩子
     */
    private function getApplicationHooks() {
        if (Loader::load('Application\Hooks')) {
            self::$hooks[] = new \Application\Hooks;
        }
    }

    /**
     * 获取模块钩子
     */
    private function getModuleHooks() {
        $modules = self::getModules();
        if (! $modules) {
            return FALSE;
        }
        foreach ($modules as $module) {
            $module = self::formatPath($module);
            $path = "Module\\{$module}\\Hooks";
            if (Loader::load($path)) {
                self::$hooks[] = new $path;
            }
        }
    }

    /**
     * 执行钩子函数
     *
     * @param string $hook 钩子名
     * @param ... 变长参数
     * @return array 钩子执行结果集
     */
    public static function invokeHook() {
        $args = func_get_args();
        $hook = array_shift($args);
        $return = array();
        foreach (self::$hooks as $hooks) {
            if (method_exists($hooks, $hook)) {
                $_result = call_user_func_array(array($hooks, $hook), $args);
                if (is_array($_result)) {
                    $return = merge_options($result, $_result);
                } else {
                    $return[] = $_result;
                }
            }
        }

        return $return;
    }

    /**
     * 获取所有配置项
     *
     * @return array 所有配置项的数组
     */
    public static function & getConfig() {
        return self::$config;
    }

    /**
     * 设置全局变量
     *
     * @param string $set 要设置的变量名
     * @param mixed $value (可选)的变量值，默认为NULL
     */
    public static function setGlobal($set, & $value = NULL) {
        if (is_array($set)) {
            self::$global = array_merge(self::$global, $set);
        } else {
            self::$global[$set] = $value;
        }
    }

    /**
     * 获取全局变量
     *
     * @param string $key 要获取的变量名
     * @return mixed 变量值
     */
    public static function & getGlobal($key = '') {
        $v = $key ? self::$global[$key] : self::$global;
        return $v;
    }

    /**
     * 接管系统错误处理并抛出异常
     *
     * @param int $code 错误码
     * @param string $message 错误消息
     * @param string $file 出错的文件名
     * @param int $line 出错的行号
     * @return bool FALSE
     */
    public function errorHandler($code, $message, $file = null,
                                 $line = null, $context = array()) {
        if (error_reporting() & $code) {
            $e['code'] = $code;
            $e['message'] = $message;
            $e['file'] = $file;
            $e['line'] = $line;
            $e['type'] = $this->getFriendlyErrorType($code);
            $debug = self::$config['common']['debug'];
            if (! $debug) {
                $tpl = @self::$config['common']['tpl']['default']['error']
                    ?: 'System\Resource\views\default\error';
            } else {
                $tpl = @self::$config['common']['tpl']['debug']['error']
                    ?: 'System\Resource\views\debug\error';
            }
            Loader::load($tpl, '.tpl.php', array('e' => $e));
            exit;
        }
    }

    /**
     * 接管系统异常处理
     *
     * @param \Exception $exception 传递的异常实例
     * @throws Exception 自定义异常实例
     */
    public function exceptionHandler($exception) {
        $message = $exception->getMessage();
        $code = $exception->getCode();
        if(!is_array($message)) {
            $trace = $exception->getTrace();
            $main = $trace[0];
            $e['type']     = get_class($exception);
            $e['code']     = $code;
            $e['message']  = $message;
            $e['file']     = $exception->getFile();
            $e['class']    = $main['class'];
            $e['function'] = $main['function'];
            $e['line']     = $exception->getLine();
            $traceInfo = '';
            $time = date("y-m-d H:i:m");
            foreach($trace as $t) {
                $traceInfo .= '[' . $time .'] '
                    . (isset($t['file']) ? $t['file'] : '')
                    . (isset($t['line']) ? "({$t['line']}) " : '')
                    . (isset($t['class']) ? $t['class'] : '')
                    . (isset($t['type']) ? $t['type'] : '')
                    . $t['function'] . '(';
                if (isset($t['args'])) {
                    $args = array();
                    foreach ($t['args'] as $arg) {
                        if (is_object($arg)) {
                            $_arg = '[' . get_class($arg) . ']';
                        } elseif (is_array($arg)) {
                            $_arg = '[array]';
                        } elseif (is_bool($arg)) {
                            $_arg = $arg ? 'true' : 'false';
                        } elseif (is_null($arg))  {
                            $_arg = 'null';
                        } else {
                            $_arg = "'" . (string) $arg . "'";
                        }
                        $args[] = '<strong>' . $_arg . '</strong>';
                    }
                    $traceInfo .= implode(', ', $args);
                    $traceInfo .=")<br/>";
                }
            }
            $e['trace']  = $traceInfo;
        }else {
            $e['type'] = $e['type'] ?: get_class($this);
            $e = $message;
        }
        $debug = self::$config['common']['debug'];
        if (! $debug) {
            $tpl = @self::$config['common']['tpl']['default'][$e['code']]
                ?: self::$config['common']['tpl']['default']['exception']
                    ?: 'System\Resource\views\default\exception';
            $tpl =  $tpl;
        } else {
            $tpl = @self::$config['common']['tpl']['debug'][$e['code']]
                ?: self::$config['common']['tpl']['debug']['exception']
                    ?: 'System\Resource\views\debug\exception';
        }
        Loader::load($tpl, '.tpl.php', array('e' => $e));
    }

    /**
     * 获取友好错误类型
     *
     * @param int $type 整型错误码
     * @param string 错误类型
     */
    private function getFriendlyErrorType($type) {
        switch($type) {
            case E_ERROR: // 1
                return 'E_ERROR';
            case E_WARNING: // 2
                return 'E_WARNING';
            case E_PARSE: // 4
                return 'E_PARSE';
            case E_NOTICE: // 8
                return 'E_NOTICE';
            case E_CORE_ERROR: // 16
                return 'E_CORE_ERROR';
            case E_CORE_WARNING: // 32
                return 'E_CORE_WARNING';
            case E_CORE_ERROR: // 64
                return 'E_COMPILE_ERROR';
            case E_CORE_WARNING: // 128
                return 'E_COMPILE_WARNING';
            case E_USER_ERROR: // 256
                return 'E_USER_ERROR';
            case E_USER_WARNING: // 512
                return 'E_USER_WARNING';
            case E_USER_NOTICE: // 1024
                return 'E_USER_NOTICE';
            case E_STRICT: // 2048
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR: // 4096
                return 'E_RECOVERABLE_ERROR';
            case E_DEPRECATED: // 8192
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED: // 16384
                return 'E_USER_DEPRECATED';
        }
        return '';
    }

    /**
     * 格式化路径
     *
     * @param string $path 输入路径如：home/module, home\Module等
     * @return string
     */
    public static function formatPath($path) {
        $path = str_replace('\\', '/', $path);
        $parts = explode('/', $path);
        foreach ($parts as $k => $v) {
            $parts[$k] = ucfirst($v);
        }
        $path = implode("\\", $parts);
        return $path;
    }

    /**
     * 获取所有模块列表
     *
     * @return array 所有模块列表
     */
    public static function getModules() {
        if (empty(self::$modules)) {
            if (isset(self::$config['common']['modules']) &&
                count(self::$config['common']['modules']) > 0) {
                self::$modules = self::$config['common']['modules'];
            } else {
                self::$modules = array();
            }
            // 调用钩子
            self::invokeHook('onGettingModules');
            foreach (self::$modules as $k => $v) {
                self::$modules[$k] = self::formatPath($v);
            }
        }

        return self::$modules;
    }

    /**
     * 设置模块列表
     *
     * @param array $modules 新模块列表
     */
    public static function setModules($modules) {
        self::$modules = array_unique(array_merge(self::$modules, $modules));
    }

    /**
     * 初始化session
     */
    private static function initSession() {
        $cookieDomain = self::$config['common']['cookieDomain'];
        // 创建个cookiedomain
        if (! $cookieDomain) {
            if (!empty($_SERVER['HTTP_HOST'])) {
                $cookieDomain = $_SERVER['HTTP_HOST'];
                // 去除开头的 ., www., 端口号
                $cookieDomain = ltrim($cookieDomain, '.');
                if (strpos($cookieDomain, 'www.') === 0) {
                    $cookieDomain = substr($cookieDomain, 4);
                }
                $cookieDomain = explode(':', $cookieDomain);
                $cookieDomain = '.' . $cookieDomain[0];
            }
        }
        if (count(explode('.', $cookieDomain)) > 2 &&
            ! is_numeric(str_replace('.', '', $cookieDomain))) {
            self::$config['Application']['cookieDomain'] = $cookieDomain;
            ini_set('session.cookie_domain', $cookieDomain);
            self::$config['common']['cookieDomain'] = $cookieDomain;
        }
        // Re-gernerate a session name.
        if (session_name() === 'PHPSESSID') {
            session_name(md5($cookieDomain));
        }
    }

    /**
     * 获取基于当前配置文件的salt（加密用）
     *
     * @return string 生成的salt字符串
     */
    public static function getSalt() {
        if (! empty(self::$config['common']['salt'])) {
            return self::$config['common']['salt'];
        } else {
            return hash('sha256', serialize(self::$config['common']));
        }
    }

    /**
     * 获取路由规则
     *
     * @return array
     */
    public static function getRoutingRules() {
        if (empty(self::$routingRules)) {
            self::$routingRules = Loader::load('Application\Config\routes');
        }
        return self::$routingRules;
    }

    /**
     * 获取程序模块
     */
    public static function getModule() {
        return self::$app->getModule();
    }

    /**
     * 获取程序控制器
     */
    public static function getController() {
        return self::$app->getController();
    }

    /**
     * 获取程序操作
     */
    public static function getAction() {
        return self::$app->getAction();
    }

    /**
     * 快捷实例化模型（Model）
     *
     * @param string $name 模型名
     * @param bool $reset 是否重新实例化
     * @return object 模型实例
     */
    public static function model($path, $reset = false) {
        $path = trim(str_replace('/', "\\", $path));
        if (strstr($path, ':')) {
            $path = str_replace(':', "\\Model\\", $path);
        }
        if ($reset || ! isset(self::$models[$path])) {
            self::$models[$path] = new $path;
        }

        return self::$models[$path];
    }

    /**
     * 快捷实例化组件（Component）
     *
     * @param string $name 组件名
     * @param array $params 组件实例化的参数
     * @param bool $reset 是否重新实例化
     * @return object 组件实例
     */
    public static function com($path, $params = array(), $reset = false) {
        $path = trim(str_replace('/', "\\", $path));
        if (strstr($path, ':')) {
            $path = str_replace(':', "\\Component\\", $path);
        }
        if ($reset || ! isset(self::$coms[$path])) {
            $rf = new \ReflectionClass($path);
            self::$coms[$path] = $rf->hasMethod('__construct')
                ? $rf->newInstanceArgs($params)
                : $rf->newInstance();
        }

        return self::$coms[$path];
    }

    /**
     * 获取程序主线程
     *
     * @return Thread
     */
    public static function getApplication() {
        return self::$app;
    }

    /**
     * 获取活动线程
     */
    public static function getActiveThread() {
        return self::$thread;
    }

    /**
     * 运行程序
     */
    public function runApplication() {
        $path = isset($_GET['p']) ? $_GET['p'] : '';
        self::$app = self::$thread = new Thread($path, $_GET);
        self::$app->run();
    }

    /**
     * 内部调用路径
     *
     * @param string $path 路径
     * @param array $args 参数
     * @return mixed 运行结果
     */
    public static function call($path, $args = array()) {
        self::$thread = new Thread($path, $args);
        return self::$thread->run();
    }

    /**
     * 内部跳转路径
     *
     * @param string $path 路径
     * @param array $args 参数
     */
    public static function forward($path, $args = array()) {
        self::$app->setPath($path);
        self::$app->run($args);
    }
}