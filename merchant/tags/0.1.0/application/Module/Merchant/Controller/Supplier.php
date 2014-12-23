<?php

namespace Module\Merchant\Controller;

use Application\Controller\Account;

class Supplier extends Account {

    /**
     * @path 订单列表
     */
     public function orderList() {
         $_GET['diner_id'] = $_GET['diner_id'] == -1 ? '' : $_GET['diner_id'];
        if($_GET['diner_id']){
            $_GET['diner_id'] = explode(",", $_GET['diner_id']) ;
        }
        $pager = $this->com('System:Pager\Pager');

        $page = $_GET['page'] ?: 0;
        $limit = $_GET['limit'] ?: 15;
        $data = $this->call(
            'api/merchant/merchantOrder/getOrderList',
            array('page'  => $page,
                'limit' => $limit,
                'start' => $_GET['start'],
                'end'   => $_GET['end'],
                'status' => isset($_GET['status']) ? (int) $_GET['status'] : -1,
                'order_id'   => $_GET['order_id'],
                'diner_id'   => $_GET['diner_id']
            )
        );

        $pagerParams = array(
            'page'    => $page,
            'limit'   => $limit,
            'total'   => $data['content']['total']
        );
        $car = $this->call(
                'api/merchant/diningcar/getItemList'
                
            );
        foreach($car['content']['list'] as $v) {
            $diner[$v->id] = $v;
        }
        $this->view->diner = $diner;
        if ($data['content']['list']) {
            $ids = array();
            foreach ($data['content']['list'] as $v) {
                $ids[] = $v->id;
            }
            $diner = array();
            
            $map = $this->call(
                'api/merchant/merchantOrder/getOrderDetail',
                array('ids' => $ids)
            );
            
            if ($map['content']) {
                $detail = array();
                foreach ($map['content'] as $v) {
                  foreach ($v as $v1 ) {
                      $detail[$v1->order_id][$v1->dish_id] = $v1;
                  }
                }
                $this->view->map = $map['content'];
                $this->view->detail = $detail;
            }
        }

        $this->view->list = $data['content']['list'];
        $this->view->pager = $pager->render($pagerParams);
        $this->view->render(':order.index');
    }

    /**
     * 添加
     */
    public function order() {
        // 获取官方菜品分类
        $result = $this->call('api/official/dishCategory/getAll');
        $categories = $result['content'];
        
        // 获取官方菜品
        $result = $this->call(
            'api/merchant/officialDish/getAll',
            array('fields' => 'id,food_name,images,supply_price,sale_price,cate_id')
        );
        $dishes = $result['content'];
        // 获取餐车
        $result = $this->call(
            'api/merchant/diningcar/getAll',
            array('fields' => 'id,merchant_id,diner_name')
        );
        $cars = $result['content'];

        $this->view->categories = $categories;
        $this->view->dishes = $dishes;
        $this->view->cars   = $cars;
        $this->view->min    = 10;
        $this->view->render(':supplier.order');
    }

    /**
     * 分装清单
     */
    public function packing() {
        $start = isset($_GET['start']) ? $_GET['start'] : date('Y-m-d');
        $end = isset($_GET['end']) ? $_GET['end'] : date('Y-m-d');
        $start = new \DateTime($start);
        $end = new \DateTime($end);
        $end = $end->setDate($end->format('Y'), $end->format('m'), (int) $end->format('d') + 1);
        // 获取所有餐车
        $result = $this->call(
            'api/merchant/diningcar/getAll',
            array('fields' => 'id,merchant_id,diner_name')
        );
        $diners = $result['content'];
        $this->view->diners = $diners;
        // 获取订单
        $result = $this->call(
            'api/merchant/merchantOrder/getList',
            array(
                'confirmed_start' => $start->getTimestamp(),
                'confirmed_end'   => $end->getTimestamp(),
                'status'     => array(3),
                'diner_id'   => $_GET['diner_id'] ?: null
            )
        );
        $orders = $result['content'];
        if ($orders['list']) {
            $dinerIds = $orderIds = $dinerOrders = $materialIds =
            $dinerMaterials = array();
            // 获取有订单的餐车
            foreach ($orders['list'] as $v) {
                $dinerIds[$v->diner_id] = $v->diner_id;
                $orderIds[$v->id] = $v->id;
                $dinerOrders[$v->diner_id][$v->id] = true;
            }
            // 获取订单详情
            $result = $this->call(
                'api/merchant/merchantOrder/getDishes',
                array('ids' => $orderIds)
            );
            $detail = $result['content'];
            $this->view->detail = $detail;

            // 获取菜品版本信息
            $dishRevisionIds = array();
            foreach ($detail as $v) {
                $dishRevisionIds[$v->dish_id] = $v->dish_revision_id;
            }
            $result = $this->call(
                'api/official/dish/getRevisionItems',
                array(
                    'ids'    => $dishRevisionIds,
                    'fields' => 'id,food_name,unit,material'
                )
            );
            $dishRevisions = $result['content'];
            // 生成餐车-原料关联
            $materialRevisionIds = array();
            foreach ($dinerIds as $dinerId) {
                foreach ($detail as $item) {
                    if (! isset($dinerOrders[$dinerId][$item->order_id])) continue;
                    $dish = $dishRevisions[$item->dish_revision_id];
                    if (! $item->material) continue;
                    foreach ($item->material as $k1 => $v1) {
                        $materialRevisionIds[$v1] = $v1;
                        $num = 0;
                        foreach ($dish->material as $v) {
                            if ($v[0] == $k1) {
                                $num = $v[1];
                                break;
                            }
                        }
                        $count = $item->quantity * $num;
                        if (! isset($dinerMaterials[$dinerId][$v1])) {
                            $dinerMaterials[$dinerId][$v1] = $count;
                        } else {
                            $dinerMaterials[$dinerId][$v1] += $count;
                        }
                    }
                }
            }
            $this->view->dinerMaterials = $dinerMaterials;

            // 获取原料版本信息
            $result = $this->call(
                'api/official/dishMaterial/getRevisionItems',
                array('ids' => $materialRevisionIds)
            );
            $materials = $result['content'];
            $this->view->materials = $materials;
        }
        $this->view->render(':supplier.packing');
    }
}