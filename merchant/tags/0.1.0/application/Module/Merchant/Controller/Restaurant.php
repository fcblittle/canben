<?php 

namespace Module\Merchant\Controller;

use Application\Controller\Account;

class Restaurant extends Account {

    /**
     * @path 首页
     */
    public function _default() {
        $data = $this->call(
            'api/merchant/restaurant/getItem',
            array('merchant_id' => $this->user->merchant_id)
        );
        $item = $data['content'];
        if ($_POST) {
            if (! $item) {
                $result = $this->call('api/merchant/restaurant/add');
            } else {
                $result = $this->call('api/merchant/restaurant/update');
            }
            if ($result['code'] === 200) {
                $this->message->set('保存成功！', 'success');
            } elseif ($result['code'] === 400 && $result['errors']) {
                foreach ($result['errors'] as $item) {
                    foreach ($item as $rule) {
                        $this->message->set($rule['message'], 'error');
                    }
                }
            } else {
                $this->message->set($result['message'], 'error');
            }
        }
        // 刷新item
        $data = $this->call(
            'api/merchant/restaurant/getItem',
            array('merchant_id' => $this->user->merchant_id)
        );
        $item = $data['content'];
        if (! $item) {
            $this->message->set('提示：请完善您的餐厅信息', 'info');
        } else {
            switch ($item->store_stauts) {
                case 0:
                    $this->message->set('提示：您的资料未通过审核，请修改后重新提交', 'error');
                    break;
                case 1:
                    $this->message->set('提示：修改资料需要重新提交审核', 'info');
                    break;
                case 3:
                    $this->message->set('提示：您的资料已提交审核，请耐心等待审核结果…', 'success');
                    break;
            }
        }
        // 餐厅特点
        $result = $this->call(
            'api/merchant/restaurantFeature/getAll'
        );
        $features = $result['content'];
        // 菜系
        $result = $this->call(
            'api/merchant/cuisine/getAll'
        );
        $cuisines = $result['content'];

        $this->view->prefix = $this->request->baseUrl() . '/';
        $this->view->item = $item;
        $this->view->features = $features;
        $this->view->cuisines = $cuisines;
        $this->view->render(':restaurant.index');
    }

}