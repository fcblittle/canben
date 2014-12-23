<?php 

namespace Module\Account\Controller;

use Application\Controller\Front;
use System\Component\Http;
use System\Component\Message\Session;

class Login extends Front {
    
    public function __construct() {
        parent::__construct();
        if ($this->auth->isLogin()) {
            $this->response->redirect(url('home'));
        }
    }
    
    /**
     * 登录页
     * 
     * @path account/login
     */
    public function _default() {
        $this->view->render(':login');
    }
    
    /**
     * 登录认证
     * 
     * @path account/login/auth
     */
    public function auth($ajax = false) {
        $filter = $this->com('System:Filter\Filter');
        $user = $filter->checkPlain(trim($_POST['name']));
        $pass = $filter->checkPlain(trim($_POST['pass']));

        if (! $user || ! $pass) {
            $this->response->json(array(
                'status' => -6,
                'info'   => '用户名或密码不能为空'
            ));
        }
        
        $url = $this->config['common']['loginApi'];
        
        $res = Http\Curl::post($url, array('user' => $user, 'pass' => $pass));

        $data = json_decode($res);

        if ($data->status == 3) {
            $this->auth->startSession($data->data);
            unset($data->data);
        }
        $this->response->json($data, true);
    }

}