<?php 

namespace Module\Customer\Controller\Api;

use Application\Controller\AppApi;

/**
 * 退款API
 * Class Refund
 * @package Module\Merchant\Controller\Api
 */
class Refund extends AppApi {

    private $model = null;

    public function init() {
        $this->model = $this->model(':Refund');
    }

    /**
     * 获取列表
     */
    public function getItemList($args = array()) {
        $args['pager'] = array(
            'page'    => $args['page'] ?: 0,
            'limit'   => $args['limit'] ?: 15
        );
        // 获取餐车
        $result = $this->call(
            'api/merchant/diningcar/getAll',
            array('status' => null)
        );
        $args['store_id'] = array_keys($result['content']);
        $result = $this->model->getItemList($args);
        if ($result === false) {
            return $this->export(array('code' => 500));
        }
        return $this->export(array('content' => $result));
    }

    /**
     * 获取多个item
     * @param array $args
     */
    public  function getItems($args = array()) {
        if (! is_array($args['id'])) {
            $id = rtrim(trim($args['id']), ',');
            $id = explode(',', $id);
        }
        if (! $id) {
            return $this->export(array(
                'code' => 400,
                'message' => 'Missing ID'
            ));
        }
        $items = $this->model->getItems($id, $this->user->merchant_id);
        if ($items['total']) {
            foreach ($items['list'] as & $v) {
                $this->formatItem($v);
            }
        }

        return $this->export(array('content' => $items));
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
        if (! in_array($data['status'], array('1', '2'))) {
            return $this->export(array(
                'code' => 400,
                'message' => 'Wrong status'
            ));
        }
        // 获取订单
        $model = $this->model(':Order');
        $item = $model->getItem(array(
            'orderno'  => $data['order_no'],
            'fields'   => 'id,orderno'
        ));
        if (! $item) {
            return $this->export(array('code' => 406));
        }
        $data['time_processed'] = REQUEST_TIME;
        $this->formatData($data);
        $this->db->beginTransaction();
        $result1 = $this->model->update($data['order_no'], $data);
        //同意时更新订单状态为已退款
        if ($data['status'] == 2) {
            $result2 = $this->db->update(
                'foodcar_order',
                array('status' => 4),
                array('orderno = ?', array($data['order_no']))
            );
        }
        $result = false;
        if ($result1 !== false && $result2 !== false) {
            $result = $this->db->commit();
        } else {
            $this->db->rollBack();
        }
        if ($result === false) {
            return $this->export(array(
                'code' => 500,
                'message' => $this->db->sth->errorInfo()
            ));
        }
        return $this->export(array('content' => $result));
    }

    private function formatData(& $data) {
        $string = $this->com('System:String\String');
        $data['remark'] = $string->truncateUtf8($data['remark'], 0, 200);
    }
}