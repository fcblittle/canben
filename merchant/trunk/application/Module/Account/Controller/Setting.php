<?php 

namespace Module\Account\Controller;

use System\Component\Http;
use Application\Controller\Account;

class Setting extends Account {
    
    public function _default() {
        $result = $this->call(
            'api/account/setting/get',
            array('uid' => $this->user->id)
        );
        $this->view->item = $result['content'];
        $this->view->render(':setting.account');
    }

    /**
     * 修改密码
     */
    public function password() {
        if ($_POST) {
            $data = array(
                'merchantid' => $this->user->id,
                'pass'   => trim($_POST['pass']),
                'newpwd' => trim($_POST['newpass']),
            );
            if (! $data['pass'] || ! $data['newpwd']) {
                $this->response->json(array(
                    'code'    => 400,
                    'status'  => -6,
                    'message' => '原密码或新密码不能为空'
                ));
            }
            $url = 'http://t.eatapp.com/index.php/api/upd_merchant';
            $res = Http\Curl::post($url, $data);
            $data = json_decode($res);
            if ($data->status == -6) {
                $this->message->set($data->info, 'error');
            } else {
                $this->message->set('密码修改成功，请妥善保存', 'success');
            }
            $this->response->redirect(url('account/setting'));
        }
    }

    /**
     * 银行账户
     */
    public function bank() {
        if ($_POST) {
            $result = $this->call(
                'api/account/setting/update'
            );
            if ($result['code'] == 200) {
                $this->message->set('修改保存成功');
            }
            $this->response->redirect(url('account/setting'));
        }
    }

}
