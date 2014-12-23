<?php 

namespace Module\Home\Controller;

use Application\Controller\Account;

class Index extends Account {

    /**
     * @path 首页
     */
    public function _default() {
        $count = array();
        // 员工总数
        $data = $this->call('api/merchant/staff/total');
        $count['staff'] = $data['content'];
        // 订单总数
        $data = $this->call('api/customer/order/total');
        $count['order'] = $data['content'];
        // 餐车总数
        $data = $this->call('api/merchant/diningcar/total');
        $count['diningcar'] = $this->user->type === "manager"?1:$data['content'];

        $this->view->count = $count;
        $this->view->render(':index');
    }

}