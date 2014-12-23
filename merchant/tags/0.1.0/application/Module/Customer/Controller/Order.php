<?php 

namespace Module\Customer\Controller;

use Application\Controller\Account;

class Order extends Account {

    /**
     * @path 首页
     */
    public function _default() {
        $pager = $this->com('System:Pager\Pager');
        $page = $_GET['page'] ?: 0;
        $limit = $_GET['limit'] ?: 15;
        $store_id = $_GET['store_id'];
        $userId = null;
        // 获取餐车
        $result = $this->call(
            'api/merchant/diningcar/getAll',
            array('fields' => 'id,diner_name')
        );
        $cars = $result['content'];
        // 餐车
        if (! $store_id || $store_id == -1) {
            $store_id = array_keys($cars);
        }
        // 指定单号
        $orderno = $_GET['type'] == 'orderno' ? $_GET['key'] : '';
        // 指定客户联系名
        $contactname = $_GET['type'] == 'contactname' ? $_GET['key'] : '';
        // 指定客户手机号
        $phone = $_GET['type'] == 'phone' ? $_GET['key'] : '';
        // 指定客户注册手机号
        if ($_GET['type'] === 'account' && $_GET['key']) {
            $result = $this->call(
                'api/customer/user/getItem',
                array(
                    'mobile_phone' => $_GET['key'],
                    'fields' => 'id,mobile_phone'
                )
            );
            $user = $result['content'];
            $userId = $user ? $user->id : 0;
        }
        $result = $this->call(
            'api/customer/order/getItemList',
            array(
                'page'     => $page,
                'limit'    => $limit,
                'store_id' => $store_id,
                'orderno'  => $orderno,
                'status'   => $_GET['status'] ?: '-1',
                'user_id'  => $userId,
                'order_person' => $contactname,
                'phone'    => $phone
            )
        );
        $orders = $result['content'];
        $pagerParams = array(
            'page'    => $page,
            'limit'   => $limit,
            'total'   => $orders['total']
        );
        if ($orders['list']) {
            $ids = $userIds = array();
            foreach ($orders['list'] as $v) {
                $ids[] = $v->id;
                $userIds[$v->user_id] = true;
            }
            // 获取客户信息
            $result = $this->call(
                'api/customer/user/getItems',
                array(
                    'id' => array_keys($userIds),
                    'fields' => 'id,nickname,mobile_phone'
                )
            );
            $this->view->users = $result['content'];
            $map = $this->call(
                'api/customer/order/getDishes',
                array('ids' => $ids)
            );
            if ($map['content']) {
                $dishIds = array();
                foreach ($map['content'] as $v) {
                    foreach ($v as $v1) {
                        $dishIds[] = $v1->food_id;
                    }
                }
                $dishIds = array_unique($dishIds);
                $dishes = $this->call(
                    'api/official/dish/getItems',
                    array('id' => $dishIds)
                );
                $this->view->map = $map['content'];
                $this->view->dishes = $dishes['content'];
            }
        }

        $this->view->cars = $cars;
        $this->view->list = $orders['list'];
        $this->view->pager = $pager->render($pagerParams);
        $this->view->render(':order.index');
    }
}