<?php 

namespace Module\Customer\Controller;

use Application\Controller\Account;

/**
 * 退款管理
 * Class Refund
 * @package Module\Merchant\Controller
 */
class Refund extends Account {

    public function __construct()
    {
        parent::__construct();

        $this->view->active = "customer/refund";
    }

    /**
     * @path 首页
     */
    public function _default() {
        $pager = $this->com('System:Pager\Pager');
        $page = $_GET['page'] ?: 0;
        $limit = $_GET['limit'] ?: 20;
        $orderno = $nickname = '';
        if ($_GET['type'] === 'order_no' && ! empty($_GET['key'])) {
            $orderno = $_GET['key'];
        }
        $_GET['status'] = isset($_GET['status']) ? (int) $_GET['status'] : 0;
        // 更新已完成订单的退款状态
        $model = $this->model(':Refund');
        $model->updateCompletedOrders();
        // 获取餐车
        $result = $this->call(
            'api/merchant/diningcar/getAll',
            array('fields' => 'id,diner_name')
        );
        $cars = $result['content'];
        // 餐车
        $store_id = $_GET['store_id'];
        if (! $store_id || $store_id == -1) {
            $store_id = array_keys($cars);
        }
        // 指定客户账号
        $account = $_GET['type'] == 'account'
            ? $_GET['key']
            : '';
        if ($account) {
            $result = $this->call(
                'api/customer/user/getItem',
                array(
                    'mobile_phone' => $account,
                    'fields'       => 'id,mobile_phone'
                )
            );
            $user = $result['content'];
            $userId = $user ? $user->id : -2;
        }
        $result = $this->call(
            'api/customer/refund/getItemList',
            array(
                'page'     => $page,
                'limit'    => $limit,
                'status'   => $_GET['status'],
                'order_no' => $orderno ?: '',
                'nickname' => $nickname ?: '',
                'store_id' => $store_id,
                'user_id'  => $userId ?: -1,
                'fields'   => 'a.*,b.user_id,b.store_id'
            )
        );
        $data = $result['content'];
        if ($data['list']) {
            // 获取用户信息
            $uids = array();
            foreach ($data['list'] as $v) {
                $uids[] = $v->user_id;
            }
            $result = $this->call(
                'api/customer/user/getItems',
                array('id' => $uids, 'fields' => 'id,nickname,mobile_phone')
            );
            $this->view->users = $result['content'];
        }
        $pagerParams = array(
            'page'    => $page,
            'limit'   => $limit,
            'total'   => $data['total']
        );

        $this->view->cars = $cars;
        $this->view->list = $data['list'];
        $this->view->pager = $pager->render($pagerParams);
        $this->view->render(':refund.index');
    }
}