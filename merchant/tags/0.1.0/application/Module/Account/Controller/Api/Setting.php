<?php 

namespace Module\Account\Controller\Api;

use Application\Controller\AppApi;

/**
 * 参数相关API
 */
class Setting extends AppApi {

    public function init() {
        $this->model = $this->model(':User');
    }

    public function get($args = array()) {
        $uid = (int) $args['uid'];
        if (! $uid) {
            return $this->export(array('code' => 400));
        }
        $result = $this->model->getItem(array(
            'id' => $uid,
            'fields' => $args['fields'] ?: '*'
        ));
        return $this->export(array('content' => $result));
    }

    /**
     * 更新
     */
    public function update($args = array()) {
        $data = $args['data'] ?: $_POST;
        $data = array(
            'bank_name' => $data['bank_name'] ?: null,
            'bank_account' => $data['bank_account'] ?: null,
            'bank_account_name' => $data['bank_account_name'] ?: null
        );
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        $result = $this->model->update($this->user->merchant_id, $data);

        return $this->export($result);
    }

    /**
     * 验证
     */
    private function validate($data) {
        $validator = $this->com('System:Validator\Validator');
        $validator->setOptions(array('breakOnError' => false));
        $rules = array();
        if (isset($data['bank_name'])) {
            $rules['bank_name'] = array(
                'name' => '开户行',
                'value' => $data['bank_name'],
                'rules' => array(
                    'required' => array(),
                )
            );
        }
        if (isset($data['bank_account'])) {
            $rules['bank_account'] = array(
                'name' => '银行卡号',
                'value' => $data['bank_account'],
                'rules' => array(
                    'required' => array(),
                )
            );
        }
        if (isset($data['bank_account_name'])) {
            $rules['bank_account_name'] = array(
                'name' => '开户名',
                'value' => $data['bank_account_name'],
                'rules' => array(
                    'required' => array(),
                )
            );
        }
        $validator->validate($rules);
        
        return $validator->getErrors();
    }
}