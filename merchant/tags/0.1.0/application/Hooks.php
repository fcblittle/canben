<?php

namespace Application;

use System\Bootstrap;
use System\Loader;
use System\Component\Db;
use System\Component\Cache;
use System\Component\Log;
use System\Component\Session;
use Application\Component\Message;
use System\Component\Http;

/**
 * 程序级钩子
 * Class Hooks
 * @package Application
 */
class Hooks {
    
    /**
     * config
     * @var array
     */
    private $config = array();

    /**
     * hook: beforeSystemInit
     */
    public function beforeSystemInit($bootstrap) {
        // 时区
        date_default_timezone_set('PRC');
        // 显示错误
        ini_set('display_errors', 1);
        // 错误级别
        ini_set('error_reporting', E_ALL ^ E_NOTICE);

        // Session
        ini_set('session.gc_maxlifetime', 604800); // 生存一周
        ini_set('session.cookie_lifetime', 0);
        ini_set('session.gc_probability', 1);
        ini_set('session.gc_divisor', 25);
        ini_set('session.name', 'degaosoft');
        ini_set('session.save_handler', 'user');
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_trans_sid', 0);
    }
    
    /**
     * hook: onSystemInit
     */
    public function onSystemInit($bootstrap) {
        Loader::load('Application\Resource\functions\function');
    }
    
    /**
     * hook: onApplicationInit
     */
    public function onApplicationInit($bootstrap) {
        
        $this->config = Bootstrap::getConfig();

        // 全局配置
        Bootstrap::setGlobal('config', $this->config);

        // MySQL
        $db = new Db\Mysql($this->config['Db']['default']['params']);
        Bootstrap::setGlobal('db', $db);

        // 日志
        $logger = new Log\Mysql($db);
        Bootstrap::setGlobal('logger', $logger);
        
        // Session
        new Session\Mysql($this->config['Session']);
        
        // 处理cookie丢失.
        if (isset($_POST['sess'])) {
            session_id($_POST['sess']);
        }
        session_start();
    }

    /**
     * hook: onControllerInit
     */
    public function onControllerInit($controller) {
        $controller->message = new Message\Session;
    }

    /**
     * hook: beforeViewRender
     */
    public function beforeViewRender($view) {
        $thread = $view->thread;
        $view->href       = $thread->request->href();
        $view->baseUrl    = $thread->request->baseUrl();
        $view->path       = $thread->request->getPath();
        $view->rawPath    = $thread->request->getRawPath();
        $view->module     = $thread->getModule();
        $view->controller = $thread->getControllerName();
        $view->action     = $thread->getAction();
        $view->queries    = $thread->request->getQueries();
        // 消息
        if (! empty($_SESSION['messages'])) {
            $view->messages = Message\Session::render();
        }
    }
}