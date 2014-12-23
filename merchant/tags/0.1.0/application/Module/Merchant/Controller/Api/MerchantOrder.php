<?php 

namespace Module\Merchant\Controller\Api;

use Application\Controller\AppApi;

/**
 * 订单相关API
 */
class MerchantOrder extends AppApi {

    public function init() {
        $this->model = $this->model(':MerchantOrder');
    }

    /**
     * 获取列表
     */
    public function getOrderList($args = array()) {
        $args['pager'] = array(
            'page'  => $args['page'] ?: 0,
            'limit' => $args['limit'] ?: 15
        );

        $args['uid'] = $this->user->merchant_id;
        $this->formatData($args);
        
        $list = $this->model->getOrderList($args);
       
        if ($list === false) {
            return $this->export(array('code' => 500));
        }
        if ($list['total']) {
            foreach ($list['list'] as & $v) {
                $this->formatItem($v);
            }
        }
        
        return $this->export(array('content' => $list));
    }

    public function getList($args = array()) {
        $args['pager'] = array(
            'page'  => $args['page'] ?: 0,
            'limit' => $args['limit'] ?: 15
        );

        $args['uid'] = $this->user->merchant_id;
        $this->formatData($args);
        $list = $this->model->getList($args);
        if ($list === false) {
            return $this->export(array('code' => 500));
        }
        if ($list['total']) {
            foreach ($list['list'] as & $v) {
                $this->formatItem($v);
            }
        }

        return $this->export(array('content' => $list));
    }

    /**
     * 获取餐车关联菜品
     * @param array $args
     * @return mixed
     */
    public function getDishes($args = array()) {
        $result = $this->model->getDishes($args['ids'], $args['fields'] ?: '*');
        if ($result) {
            foreach ($result as & $v) {
                if ($v->material) {
                    $v->material = json_decode($v->material, true);
                }
            }
        }
        return $this->export(array('content' => $result));
    }

    /**
     * 获取订单详情
     */
    public function getOrderDetail($args = array()) {
        $ids = $args['ids'];
        if (! is_array($ids)) {
            $ids = rtrim(trim($ids), ',');
            $ids = explode(',', $ids);
        }
        if (! $ids) {
            return $this->export(array(
                'code' => 400,
                'message' => 'Missing ID'
            ));
        }
        $items = $this->model->getOrderDetail($ids, $this->user->merchant_id);
        
        if ($items === false) {
            return $this->export(array('code' => 500));
        }
        return $this->export(array('content' => $items));
    }

    private function formatItem(& $item) {
        $item->created && $item->created = date('Y-m-d H:i:s', $item->created);
    }

    /**
     * 更新
     */
    public function update($args = array()) {
        $data = $args['data'] ?: $_POST;
        if (! $data['order_no']) {
            return $this->export(array(
                'code' => 400, 
                'message' => 'Missing ID'
            ));
        }
        if (! in_array($data['status'], array('0', '1', '2'))) {
            return $this->export(array(
                'code' => 400,
                'message' => 'Wrong status'
            ));
        }
       
        $item = $this->model->getItem(array(
            'id' => $data['order_no'],
            'merchant_id' => $this->user->merchant_id
        ));
        
        if (!$item) {
            return $this->export(array('code' => 406));
        }
        $result = $this->model->update($data['order_no'], array('status' => $data['status'] ));
        if ($result === false) {
            return $this->export(array(
                'code' => 500,
                'message' => $this->db->sth->errorInfo()
            ));
        }
        return $this->export(array('content' => $result));
    }


    private function formatData(& $data) {
        if ($data['start']) {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $data['start']);
            $data['start'] = $date->getTimestamp();
        }
        if ($data['end']) {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $data['end']);
            $data['end'] = $date->getTimestamp();
        }
    }

    /**
     * 获取单个
     */
    public function getItem($args = array()) {
        $id = (int) $args['id'];
        $fields = $args['fields'] ?: '*';
        if (! $id) {
            return $this->export(array(
                'code' => 400,
                'message' => 'Missing ID'
            ));
        }
        $item = $this->model->getItemById($id, $this->user->merchant_id, $fields);
        $this->formatItem($item);

        return $this->export(array('content' => $item));
    }

    /**
     * 获取多个
     */
    public function getItems($args = array()) {
        $ids = $args['ids'];
        $items = array();
        if (! is_array($ids)) {
            $ids = rtrim(trim($ids), ',');
            $ids = explode(',', $ids);
        }
        if (! $ids) {
            return $this->export(array(
                'code' => 400,
                'message' => 'Missing ID'
            ));
        }
        $items = $this->model->getItems(array(
            'ids' => $ids,
            'merchant_id' => $this->user->merchant_id,
            'fields' => $args['fields'] ?: '*'
        ));
        if ($items) {
            foreach ($items as & $v) {
                $this->formatItem($v);
            }
        }

        return $this->export(array('content' => $items));
    }
    
    /**
     * 新订单
     */
    public function add($args = array()) {
        $data = $args['data'] ?: $_POST['data'];
        if (! $data) {
            return $this->export(array('code' => 400));
        }
        $data = json_decode(stripslashes($data), true);
        $carIds = $dishIds = array();
        foreach ($data as $k => $v) {
            $dishIds[] = $k;
            foreach ($v as $v1) {
                $carIds[] = $v1[0];
            }
        }
        // 检查餐车
        $result = $this->call(
            'api/merchant/diningcar/getItems',
            array(
                'id' => $carIds,
                'fields' => 'id,diner_name'
            )
        );
        $cars = $result['content'];
        if (! $cars) {
            return $this->export(array('code' => 406));
        }
        // 检查菜品
        $result = $this->call(
            'api/merchant/officialDish/getItems',
            array(
                'id' => $dishIds,
                'fields' => 'id,revision_id,food_name,supply_price,material'
            )
        );
        $dishes = $result['content'];
        if (! $dishes) {
            return $this->export(array('code' => 406));
        }
        // 获取原料
        $materialIds = array();
        foreach ($dishes as $v) {
            foreach ($v->material as $v1) {
                $materialIds[$v1[0]] = $v1[0];
            }
        }
        $result = $this->call(
            'api/official/dishMaterial/getItems',
            array('ids' => $materialIds)
        );
        $materials = $result['content'];
        $list = array();
        foreach ($cars as $k => $v) {
            $list[$k] = array();
            foreach ($dishes as $k1 => $v1) {
                if (! $data[$k1]) continue;
                // 获取原料最新版本
                $materialRevisions = array();
                foreach ($v1->material as $material) {
                    $item = $materials[$material[0]];
                    $materialRevisions[$item->id] = $item->revision_id;
                }
                foreach ($data[$k1] as $v2) {
                    if ($v2[0] == $k) {
                        $revisionId = $v1->revision_id ?: $k1;
                        $list[$k][$k1] = array(
                            $v1->supply_price,
                            $v2[1],
                            $revisionId,
                            json_encode($materialRevisions)
                        );
                    }
                }
            }
        }
        // 创建订单
        $result = $this->model->add($this->user->merchant_id, $list);
        if ($result === false) {
            return $this->export(array(
                'code' => 500,
                'message' => $this->db->sth->errorInfo()
            ));
        }
        return $this->export(array('content' => $result));
    }

    /**
     * @path 支付
     */
    public function pay() {
        if (! $_GET['ids']) {
            $this->error('缺少订单id', 400);
        }
        // 获取订单
        $result = $this->call(
            'api/merchant/merchantOrder/getItems',
            array(
                'ids'    => $_GET['ids'],
                'fields' => 'id,merchant_id,total_price,status'
            )
        );
        $orders = $result['content'];
        $total = 0;
        if ($orders) {
            foreach ($orders as $v) {
                if ($v->merchant_id != $this->user->merchant_id || $v->status != 0) continue;
                $orderIds[] = $v->id;
                $total += $v->total_price;
            }
        }
        if ($orderIds) {
            // 添加支付记录
            $model = $this->model(':Payment');
            $data = array(
                'merchant_id' => $this->user->merchant_id,
                'order_id' => implode(',', $orderIds),
                'created'  => REQUEST_TIME
            );
            $result = $model->add($data);
            if ($result === false) {
                return $this->export(array(
                    'code' => 500,
                    'message' => $this->db->sth->errorInfo()
                ));
            }
        }

        $callback = $this->request->baseUrl() . '/api/merchant/payment/callback';
        $data = array(
            'v_mid'       => '22931671',
            'v_oid'       => $result,
            'v_amount'    => $total,
            'v_moneytype' => 'CNY',
            'v_url'       => $this->request->baseUrl() . '/merchant/order/paid',
            'remark1'     => $this->user->merchant_id,
            'remark2'     => "[url:={$callback}]"
        );
        $data['v_md5info'] = strtoupper(md5(
              $data['v_amount']
            . $data['v_moneytype']
            . $data['v_oid']
            . $data['v_mid']
            . $data['v_url']
            . '4ebcaf626fe699c4e7a54341e3a5842d'
        ));
        $return = array(
            'total'   => $total,
            'payment' => $data
        );

        return $this->export(array('content' => $return));
    }

}