<?php 

namespace Module\Account\Controller;

use Application\Controller\Front;

class Register extends Front {
    
    /**
     * 注册页
     */
    public function __call($name, $args) {
        // 已登录
        if ($this->auth->isLogin()) {
            $this->com('Http\Response')->redirect('user/' . $this->user->uid);
        }
        // 未提交，显示页面
        if (empty($_POST)) {
            $data = array();
            if (isset($_SESSION['openid'])) {
                $openid = $_SESSION['openid'];
                $map = array(
                    'qqdenglu'  => 'qq',
                    'sinaweibo' => 'weibo',
                    'baidu'     => 'baidu',
                    'qqweibo'   => 'qqweibo',
                    'renren'    => 'renren',
                    'kaixin'    => 'kaixin'
                );
                $data['name'] = $map[$openid->media_type] . '_' . $openid->username;
            }
            $this->view->data = $data;
            $this->view->token = $this->view->form()->getToken();
            return $this->view->render('account/register');
        }
        // 非法表单提交
        if (! $this->view->form()->validToken($_POST['token'])) {
            $this->com('Http\Response')->json(array(
                'type' => 'error',
                'message' => 'Invalid form post.'
            ));
        }
        // 注册
        $_SESSION['trials'] += 1;
        $hash = $this->com('Crypt\Hash');
        $data = array(
            'license' => $_POST['license'],
            'name'    => trim($_POST['name']),
            'email'   => trim($_POST['email']),
            'pass'    => trim($_POST['pass']),
            'salt'    => $hash->randomString(),
            'created' => REQUEST_TIME
        );
        if (isset($_POST['captcha'])) {
            $data['captcha'] = strtolower(trim($_POST['captcha']));
        }
        if (! $this->verify($data)) {
            $this->view->data = $data;
            $this->view->token = $this->view->form()->getToken();
            return $this->view->render('account/register');
        }
        $data['hashedPass'] = $hash->password($data['pass'], $data['salt']);
        $model = $this->model('User', 'Share');
        $uid = $model->add($data);
        if ($uid) {
            unset($_SESSION['trials']);
            // 添加openid绑定
            if (isset($_SESSION['openid'])) {
                $model->addOpenid(array(
                    'uid'       => $uid,
                    'type'      => $_SESSION['openid']->media_type,
                    'openid'    => $_SESSION['openid']->media_uid,
                    'socialUid' => $_SESSION['openid']->social_uid,
                    'created'   => REQUEST_TIME
                ));
                unset($_SESSION['openid']);
            }
            $this->auth->auth($data['name'], $data['pass']);
            $this->com('Http\Response')->redirect(url('user/' . $uid));
        } else {
            $this->com('Message\Session')->set('注册失败，请稍后再试。', 'error');
        }
    }
    
    /**
     * 注册验证
     */
    public function verify($data) {
        $message = $this->com('Message\Session');
        // 验证码错误
        if (isset($data['captcha']) && ($data['captcha'] != $_SESSION['captcha'])) {
            $message->set('您输入的验证码不正确', 'error');
            return FALSE;
        }
        // 未同意用户协议
        if (strtolower($data['license']) !== 'on') {
            $message->set('您需要先同意《爱搭配用户使用协议》', 'error');
            return FALSE;
        }
        // 用户名长度错误
        $len = mb_strlen($data['name'], 'UTF-8');
        if ($len > 30 || $len < 2) {
            $message->set('用户名长度为2-30个字符', 'error');
            return FALSE;
        }
        // 用户名不能以数字开头
        if (is_numeric(substr($data['name'], 0, 1))) {
            $message->set('用户名不能以数字开头', 'error');
            return FALSE;
        }
        // 用户名包含特殊字符
        // TODO: 规则待完善
        if (preg_match_all('#[`~!@\#$%^&*()+={\[}\]|\\:;"\'<,>.?/]#', $data['name'], $matches)) {
            $chars = htmlspecialchars(implode(',', array_unique($matches[0])));
            $message->set('用户名包含特殊字符: ' . $chars, 'error');
            return FALSE;
        }
        // 用户名已注册
        $model = $this->model('User', 'Share');
        $item = $model->getItemByName($data['name'], 'uid');
        if (! empty($item)) {
            $message->set('用户名已存在', 'error');
            return FALSE;
        }
        // 密码长度错误
        $len = strlen($data['pass']);
        if ($len > 20 || $len < 6) {
            $message->set('密码长度为6-20个字符', 'error');
            return FALSE;
        }
        // 密码为纯数字
        if (is_numeric($data['pass'])) {
            $message->set('密码不能为纯数字', 'error');
            return FALSE;
        }
        // 邮箱格式错误
        if (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $message->set('邮箱格式错误', 'error');
            return FALSE;
        }
        // 邮箱已被注册
        $item = $model->getItemByEmail($data['email'], 'uid');
        if (! empty($item)) {
            $message->set('邮箱已存在', 'error');
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * ajax验证用户名
     */
    public function verifyName() {
        // 用户名已注册
        $model = $this->model('User', 'Share');
        $item = $model->getItemByName($name, 'uid');
        if (! empty($item)) {
            $this->com('Http\Response')->json(array(
                'type' => 'error',
                'message' => '用户名已存在'
            ));
        }
    }
    
    /**
     * ajax验证邮箱
     */
    public function verifyEmail() {
        $model = $this->model('User', 'Share');
        $item = $model->getItemByEmail($email, 'uid');
        if (! empty($item)) {
            $this->com('Http\Response')->json(array(
                'type' => 'error',
                'message' => '邮箱已存在'
            ));
        }
    }
}