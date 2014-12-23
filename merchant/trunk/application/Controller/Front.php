<?php

namespace Application\Controller;

use System\Component\Http;

/**
 * 前端控制器
 * 
 * 初始化系统变量等
 */
class Front extends \System\Controller {
    
    public function __construct() {
        parent::__construct();
        $this->auth = $this->com('Application:Account\Auth');
        $this->user =  & $this->auth->user;
        $this->view->_USER = & $this->auth->user;

        $this->event = $this->com('Application:Util\Event');

    }

    /**
     * 基本认证
     *
     * @param bool $ajax 是否ajax请求
     */
    protected function auth($ajax = false) {
        if (! $this->user->id) {
            if (! $ajax) {
                $this->response->redirect('/account/login');
            }
            $this->response->json(array(
                'type' => 'error',
                'code' => 403,
                'message' => '您需要登录以完成此操作'
            ));
        }
    }

    /**
     * 快捷调用路径
     * 
     * @param string $path 要调用的路径
     * @param array $args 参数
     * @return mixed
     */
    public function call($path, $args = array()) {
        $args['export'] = '';
        return parent::call($path, $args);
    }
    
    
    /**
     * 显示错误
     * 
     * @param string $message 错误消息
     * @param int $code 错误码
     * @param bool $JSON 是否json输出
     */
    protected function error($message = '', $code = 0, $JSON = false) {
        $message = $message ?: Http\Response::$statusTexts[$code];
        if (! $JSON) {
            throw new \System\Exception($message, $code);
        } else {
            $this->response->json(array(
                'code' => $code,
                'message' => $message
            ));
        }
    }
    
    /**
     * 获取系统变量
     * 
     * @param string $name 变量名
     * @param mixed $value 变量值
     * @return bool 执行结果
     */
    protected function setVariable($name, $value = NULL) {
        $model = $this->model('Application:Variable');
        return $model->set($name, $value);
    }
    
    /**
     * 获取系统变量
     * 
     * @param string $name 变量名
     * @return bool 执行结果
     */
    protected function getVariable($name) {
        $model = $this->model('Application:Variable');
        return $model->get($name);
    }
    
    /**
     * 删除系统变量
     * 
     * @param string $name 变量名
     * @return bool 执行结果
     */
    protected function deleteVariable($name) {
        $model = $this->model('Application:Variable');
        return $model->get($name);
    }

}