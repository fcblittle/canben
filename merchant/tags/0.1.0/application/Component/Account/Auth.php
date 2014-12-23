<?php

namespace Application\Component\Account;

use System\Bootstrap, 
    System\Component\Http,
    System\Component\Crypt;

class Auth {
    
    /**
     * User object
     * @var object
     */
    public $user      = NULL;
    
    /**
     * Session timeout.
     * @var int
     */
    private $timeout   = 0;
    
    /**
     * Cookie domain.
     * @var string
     */
    public $cookieDomain = '';
    
    /**
     * Is user logged in?
     * @var bool
     */
    private $isLogin  = false;
   
    function __construct($params = array()) {
        $config      = Bootstrap::getConfig();
        $this->db    = Bootstrap::getGlobal('db');
        $this->model = Bootstrap::model('Application:User');
        $this->cookieDomain = $config['common']['cookieDomain'];
        if ($this->isLogin()) {
            $this->user = $_SESSION['user'];
        } else {
            $this->user = (object) array('uid' => 0);
        }
    }
    
    /**
     * 用户是否登录？
     * 
     * @return bool
     */
    public function isLogin() {
        return (bool) $_SESSION['user']->merchant_id;
    } 
    
    /**
     * Set timeout.
     */
    public function setTimeout($sec) {
        $this->timeout = $sec;
    }
    
    /**
     * auth.
     * 
     * @param $name username.
     * @param $pass password.
     * @return user array if auth OK/ FALSE if auth failed.
     */
    public function auth($name, $pass) {
        if (! $this->isLogin()) {
            if (filter_var($name, FILTER_VALIDATE_EMAIL) === FALSE) {
                $user = $this->model->getItemByName($name);
            } else {
                $user = $this->model->getItemByEmail($name);
            }
            if ($user && $user->pass === Crypt\Hash::password($pass, $user->salt)) {
                $this->startSession($user);
            }
        }
        
        return $this->user;
    }
    
    /**
     * Set session
     * 
     * @param object $user
     */
    public function startSession($user) {
        $this->isLogin = true;
        $this->user = $user;
        $_SESSION['user'] = & $this->user;
        if ($this->timeout) {
            setcookie(session_name(), session_id(), REQUEST_TIME + $this->timeout, '/', $this->cookieDomain);
        }
        //$this->model->update($user->uid, array('login' => REQUEST_TIME));
    }
    
    /**
     * destroy session.
     */
    public function logout() {
        $this->isLogin = false;
        session_destroy();
        setcookie(session_name(), '', REQUEST_TIME - 10000, '/', $this->cookieDomain);
    }

}