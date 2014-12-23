<?php 

namespace Module\Account\Controller;

use Application\Controller\Front;

class Password extends Front {
    
    /**
     * 忘记密码
     */
    public function forget() {
        if ($_POST) {
            // 非法表单提交
            if (! $this->view->form()->validToken($_POST['token'])) {
                $this->com('Http\Response')->json(array(
                    'type' => 'error',
                    'message' => 'Invalid form post.'
                ));
            }
            $data = array(
                'email'   => trim($_POST['email']),
                'captcha' => trim($_POST['captcha'])
            );
            if ($this->verify($data)) {
                $setting = $this->getVariable('system.setting.email');
                $mailer = $this->com('Mail\Mailer', 'Share');
                $mailer->setParams($setting);
                $hash = $this->getHash(REQUEST_TIME, $this->item);
                $url = url('account/password/reset/' . $this->item->uid . '/' . 
                    $this->com('Crypt\Hash')->int2Alpha(REQUEST_TIME) . '/' . $hash, 
                    array('absolute' => TRUE));
                $this->view->url = $url;
                $body = $this->view->fetch('email/password.reset');
                $status = $mailer->send(array(array($email)), '爱搭配 - 重置您的密码', $body);
                if ($status) {
                    $this->com('Message\Session')->set('一封密码重置邮件已发到您的邮箱，请于一小时内查收并完成密码修改', 'status');
                } else {
                    $this->com('Message\Session')->set('邮件发送失败，请稍后重试', 'error');
                }
            }
        }
        $this->view->token = $this->view->form()->getToken();
        $this->view->render('account/password.forget');
    }
    
    /**
     * 重置密码
     */
    public function reset() {
        if ($this->auth->isLogin()) {
            $this->com('Http\Response')->redirect(url('user/' . $this->user->uid));
        }
        if ($_POST) {
            $data = array(
                'pass' => trim($_POST['pass']),
                'pass2' => trim($_POST['pass2'])
            );
            if ($this->verifyPass($data)) {
                $model = $this->model('User', 'Share');
                $password = $this->com('Crypt\Hash')->password($data['pass'], $this->user->salt);
                $result = $model->update($this->user->uid, array('pass' => $password));
                if ($result) {
                    $message->set('密码修改成功，要好好保存哦^_^', 'status');
                } else {
                    $message->set('系统错误，请稍后尝试', 'error');
                }
                $this->com('Http\Response')->redirect(url('user/' . $this->user->uid));
            }
        }
        $timeout = 3600;
        $uid       = (int) $this->com('Http\Request')->arg(3);
        $timestamp = $this->com('Crypt\Hash')->alpha2Int($this->com('Http\Request')->arg(4));
        $hash      = $this->com('Http\Request')->arg(5);
        if (! $uid || ! $timestamp) {
            $this->view->render('common/400');
        }
        $model = $this->model('User', 'Share');
        $user = $model->getItemById($uid);
        if (! $user) {
            $this->view->render('common/400');
        }
        // 超时或hash值不对
        if ($timestamp > REQUEST_TIME || ($timestamp + $timeout < REQUEST_TIME) ||
            ($this->getHash($timestamp, $user) !== $hash)) {
            $this->view->op = 'show';
        } else {
            $this->view->op = 'edit';
            $this->auth->startSession($user);
        }
        $this->view->token = $this->view->form()->getToken();
        $this->view->render('account/password.reset');
    }
    
    /**
     * 验证邮箱
     */
    private function verify($data) {
        $message = $this->com('Message\Session');
        // 验证码错误
        if ($data['captcha'] != $_SESSION['captcha']) {
            $message->set('您输入的验证码不正确', 'error');
            return FALSE;
        }
        // 邮箱格式错误
        if (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $message->set('邮箱格式错误', 'error');
            return FALSE;
        }
        $model = $this->model('User', 'Share');
        // 邮箱不存在
        $this->item = $model->getItemByEmail($data['email'], 'uid');
        if (empty($this->item)) {
            $message->set('邮箱不存在', 'error');
            return FALSE;
        }
        return TRUE;
    }
    
    private function verifyPass($data) {
        $message = $this->com('Message\Session');
        // 两次输入不同
        if ($data['pass'] != $data['pass2']) {
            $message->set('您两次输入的密码不同', 'error');
            return FALSE;
        }
        return TRUE;
    }

    private function getHash($timestamp, $user) {
        return $this->com('Crypt\Hash')->base64Hmac($timestamp . $user->login, 
            $user->salt . $user->pass);
    }
}