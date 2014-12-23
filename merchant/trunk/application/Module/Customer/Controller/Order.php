<?php 

namespace Module\Customer\Controller;

use Application\Controller\Account;

class Order extends Account {

    public function __construct()
    {
        parent::__construct();

        $this->view->active = "customer/order";
    }


    /**
     * @path 首页
     */
    public function _default() {
        if ($this->user->type == 'merchant') {
            $this->getMerchantOrderView();
        } else {
            $this->orderOnline();
        }
    }

    /**
     * 获取商户订单页
     * 
     * @param method get
     * @return array $diner,
     *         array $manager,
     *         array $order
     */
    public function getMerchantOrderView()
    {
        if (! $this->checkMerchantPermission()) {
            $this->message->set('您无此权限！', 'error');
            $this->response->redirect('/customer/order');
        }

        $diner = $manager = $order = array();

        // 获取餐车
        $result = $this->call(
            'api/merchant/diningcar/getAll',
            array('fields' => 'id,diner_name', 'status' => 1)
        );
        if ($result['code'] != 200) {
            $this->error('数据库查询失败！', 'error');
        }
        $diner = $result['content'];
        $dinerIds = array_keys($diner);

        // 获取经营者
        $result = $this->call(
            'api/merchant/diningcar/getDinerManager',
            array(
                'fields'   => 'id, diner_id, username, realname',
                'diner_id' => $dinerIds
            )
        );
        if ($result['code'] != 200) {
            $this->error('数据库查询失败！', 'error');
        }
        $manager = $result['content'];

        // 获取查询条件
        $cond = $this->getSearchCondition(array(
            'view' => 'orderStatistics', 
            'diner_id' => $dinerIds)
        );
        if ($cond === false) {
            $this->error('不存在此餐车！');
        }

        // 线上订单
        $result = $this->call(
            'api/customer/order/getItemList',
            array(
                'store_id' => $cond['diner_id'],
                'order_date'     => $cond['date']
            )
        );
        if ($result === false) {
            $this->error('数据库查询失败！');
        }
        $orderOnline = $result['content'];

        // 获取餐车订单（线上线下）
        $order['online']  = $this->getOrderAmount($orderOnline['list']);
        $order['offline'] = $this->getOrderOfflineAmount(array(
            'store_id' => $cond['diner_id'],
            'date'     => $cond['date']
        ));

        $orderDate = array_merge(
            (array) $order['online']['date'], 
            (array) $order['offline']['date']
        );

        $this->view->diner = $diner;
        $this->view->manager = $manager;
        $this->view->orderDinerOnline  = $order['online']['list'];
        $this->view->orderDinerOffline = $order['offline']['list'];
        $this->view->orderDate = array_unique($orderDate);
        $this->view->dateInterval = $this->getDateInterval($cond['date']);
        $this->view->render(':order.merchant.index');
    }

    /**
     * 获取经营者视图
     * 线上订单
     * 
     * @param method get
     * 
     */
    public function orderOnline()
    {
        $pager = $this->com('System:Pager\Pager');
        $page = $_GET['page'] ?: 0;
        $limit = $_GET['limit'] ?: 15;
        $userId = null;
        // 获取餐车
        $result = $this->call(
            'api/merchant/diningcar/getAll',
            array('fields' => 'id,diner_name')
        );
        $cars = $result['content'];
        // 餐车
        $store_id = array_keys($cars);

        $paramCond = array(
            'view'     => 'orderList',
            'type'     => $_GET['type'] ?: '-1',
            'key'      => $_GET['key'] ?: '',
            'diner_id' => $store_id
        );
        $cond = $this->getSearchCondition($paramCond);
        if ($cond == false) {
            $this->error('数据库查询失败！');
        }

        $result = $this->call(
            'api/customer/order/getItemList',
            array(
                'page'         => $page,
                'limit'        => $limit,
                'store_id'     => $cond['store_id'],
                'order_date'   => $cond['date'],
                'orderno'      => $cond['orderno'],
                'status'       => $_GET['status'] ?: '-1',
                'user_id'      => $userId,
                'order_person' => $cond['contactname'],
                'phone'        => $cond['phone']
            )
        );
        $orders = $result['content'];
        $pagerParams = array(
            'page'    => $page,
            'limit'   => $limit,
            'total'   => $orders['total']
        );
        $count = $amount = 0;
        if ($orders['list']) {
            $ids = $userIds = array();
            foreach ($orders['list'] as $v) {
                $ids[] = $v->id;
                $userIds[$v->user_id] = true;

                $amount += $v->order_amount;
                $count++;
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
        $this->view->orderStatistics = array(
            'diner'  => is_array($cond['store_id']) ? $cond['store_id'][0] : $cond['store_id'],
            'count'  => $count,
            'amount' => $amount
        );
        $this->view->pager = $pager->render($pagerParams);
        $this->view->render(':order.online');
    }

    /**
     * 获取经营者视图
     * 线下订单
     * 
     * @param method get
     * - int store_id  餐车id
     * - string date 订单日期
     */
    public function orderOffline()
    {
        // 获取餐车
        $result = $this->call(
            'api/merchant/diningcar/getAll',
            array('fields' => 'id,diner_name')
        );
        $cars = $result['content'];
        // 餐车
        $store_id = array_keys($cars);
        if (empty($store_id)) {
            exit();
        }

        $cond = $this->getSearchCondition(array(
            'view' => 'orderOffline',
            'diner_id' => $store_id,
        ));
        if ($cond == false) {
            $this->error('数据库查询失败！');
        }

        $param = array(
            'store_id' => $cond['diner_id'],
            'date'     => $cond['date']
        );
        $orderOffline = $this->getOrderOffline($param);

        // 菜品
        $dishIds = array();
        foreach ($orderOffline as $item) {
            $dishIds[] = $item->dish_reversion_id;
        }
        $result = $this->call(
            'api/merchant/officialDish/getDishRevision',
            array(
                'id' => $dishIds
            )
        );
        $dishes = array();
        if ($result['content']) {
            foreach ($result['content'] as $item) {
                $dishes[$item->id] = $item;
            }
        }

        $this->view->orderOffline = $orderOffline;
        $this->view->dishes = $dishes;
        $this->view->render(':order.offline');
    }

    /**
     * 获取商户视图订单
     * 
     * @param array $orderOnline 线上订单
     * 
     * @return array $orderDinerOnline
     */
    protected function getOrderAmount($orderOnline)
    {
        $orderDinerOnline = $orderDinerOffline = $orderOnlineDate = array();

        if (empty($orderOnline)) {
            return array();
        }

        // 线上订单
        foreach ($orderOnline as $item) {
            $date = $this->getOrderDate($item->insert_time);

            if (! isset($orderDinerOnline[$date][$item->store_id])) {
                $orderDinerOnline[$date][$item->store_id] = $item->order_amount;
                continue;
            }
            $orderDinerOnline[$date][$item->store_id] += $item->order_amount;

            $orderOnlineDate[] = $date;
        }

        return array(
            'list' => $orderDinerOnline,
            'date' => $orderOnlineDate
        );
    }
    
    /**
     * 获取线下订单
     * 
     * @param array $args
     * -int store_id 餐车id
     * -string date  日期
     * 
     * @return array orderDinerOffline
     * -string date 日期
     * --diner_id 餐车id
     */
    protected function getOrderOffline($args)
    {
        // 线下订单
        $result = $this->call(
            'api/customer/orderOffline/getItem',
            array(
                'diner_id'   => $args['store_id'],
                'order_date' => $args['date']
            )
        );
        $orderOffline = $result['content'];

        return $orderOffline;
    }

    protected function getOrderOfflineAmount($args)
    {
        $orderOffline = $this->getOrderOffline($args);

        if (empty($orderOffline)) {
            return array();
        }

        $orderOfflineDate = array();
        foreach ($orderOffline as $offlineItem) {
            $date = date('Y-m-d', $offlineItem->created);

            if (isset($orderDinerOffline[$date][$offlineItem->diner_id])) {
                $orderDinerOffline[$date][$offlineItem->diner_id]['amount'] += $offlineItem->count * $offlineItem->price;
                continue;
            }

            $orderDinerOffline[$date][$offlineItem->diner_id]['amount'] = $offlineItem->count * $offlineItem->price;
            $orderOfflineDate[] = $date;
        }

        return array(
            'list' => $orderDinerOffline,
            'date' => $orderOfflineDate
        );
    }

    private function getSearchCondition($args = array())
    {
        $params = $_GET;
        $cond = array();

        // 验证获取的餐车id是否属于该商户
        if (is_numeric($params['diner_id']) &&
            ! in_array($params['diner_id'], $args['diner_id'])) {
            return false;
        }

        if ($args['view'] == 'orderStatistics') {
            $cond['diner_id'] = ! empty($params['diner_id']) && is_numeric($params['diner_id']) 
                                ? $params['diner_id'] 
                                : $args['diner_id'];
            $cond['date'] = $params['date'] ?: array();
        } else if ($args['view'] == 'orderOffline') {
            $cond['date'] = $params['date'] ?: date('Y-m-d', REQUEST_TIME);
            $cond['diner_id'] = $params['diner_id'] ?: null;
        } else if ($args['view'] == 'orderList') {
            $cond['store_id'] = $params['diner_id'] ?: $args['diner_id'];
            // 指定单号
            $cond['orderno'] = $args['type'] == 'orderno' ? $args['key'] : '';
            // 指定客户联系名
            $cond['contactname'] = $args['type'] == 'contactname' ? $args['key'] : '';
            // 指定客户手机号
            $cond['phone'] = $args['type'] == 'account' ? $args['key'] : '';
            // 指定日期
            $cond['date'] = $params['date'] ?: date('Y-m-d', REQUEST_TIME);
            // 指定客户注册手机号
            if ($args['type'] === 'account' && $args['key']) {
                $result = $this->call(
                    'api/customer/user/getItem',
                    array(
                        'mobile_phone' => $args['key'],
                        'fields' => 'id,mobile_phone'
                    )
                );
                $user = $result['content'];
                $cond['userId'] = $user ? $user->id : 0;
            }
        }

        return $cond;
    }

    /**
     * 获取时间区间
     */
    public function getDateInterval($date)
    {
        $cur_date = date('Y-m-d', REQUEST_TIME);
        if (! empty($date['start'])) {
            list($year, $month, $day) = explode('-', $date['start']);
            $start = mktime(0, 0, 0, $month, $day, $year);
        } else {
            list($year, $month, $day) = explode('-', $cur_date);
            $start = mktime(0, 0, 0, $month, 1, $year);
        }

        if (! empty($date['end'])) {
            list($year, $month, $day) = explode('-', $date['end']);
            $end = mktime(0, 0, 0, $month, $day, $year);
        } else {
            list($year, $month, $day) = explode('-', $cur_date);
            $end = mktime(0, 0, 0, $month, $day, $year);
        }

        return array(
            'start'  => date('Y-m-d', $start),
            'end'    => date('Y-m-d', $end)
        );
    }

    /**
     * 获取日期
     */
    protected function getOrderDate($time)
    {
        list($date, $time) = explode(' ', $time);
        return $date;
    }
}