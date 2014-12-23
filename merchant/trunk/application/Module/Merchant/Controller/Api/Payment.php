<?php 

namespace Module\Merchant\Controller\Api;

use Application\Controller\AppApi;

/**
 * 订单相关API
 */
class Payment extends AppApi {

    public function init() {
        $this->model = $this->model(':Payment');
    }

    public function permission() {
        return array(
            'public' => array('callback')
        );
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
        $item = $this->model->getItemById($id, $this->user->id, $fields);
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
            'merchant_id' => $this->user->id,
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
     * 新支付
     */
    public function add($args = array()) {
        $orderIds = $args['orderIds'];
        if (! $orderIds) {
            return $this->export(array('code' => 400));
        }
        $data = array(
            'merchant_id' => $this->user->id,
            'data' => is_array($orderIds) ? implode(',', $orderIds) : $orderIds
        );
        $result = $this->model->add($data);
        if ($result === false) {
            return $this->export(array(
                'code' => 500,
                'message' => $this->db->sth->errorInfo()
            ));
        }
        return $this->export(array('content' => $result));
    }

    /**
     * 支付完成回调
     * TODO: 日志记录
     */
    public function callback() {
        $data = $_POST;
        _log('payment.callback', json_encode($data));
        // 获取支付信息
        $modelPayment = $this->model(':Payment');
        $item = $modelPayment->getItem(array(
            'id' => $data['v_oid'],
            'merchant_id' => $data['remark1']
        ));
        // 错误的支付信息
        if (! $item || $item->status == 1) {
            _log('payment.callback', 'wrong payment info');
            echo 'error';
            return false;
        }
        // 已更新过
        if ($item->status == 2) {
            _log('payment.callback', 'updated');
            echo 'ok';
            return true;
        }
        // 更新支付信息
        $result = $this->updateStatus($data, $item);
        if (! $result) {
            _log('payment.callback', 'faild:' . json_encode($this->db->sth->errorInfo()));
            echo 'error';
            return false;
        }
        _log('payment.callback', 'ok');
        echo 'ok';
    }

    /**
     * 更新支付状态
     * @param $data
     * @param $item
     * @return bool
     */
    private function updateStatus($data, $item) {
        $this->db->beginTransaction();
        $result = $this->model->update(
            array('status' => 2),
            array('id = ?', array($data['v_oid']))
        );
        if ($result === false) {
            $this->db->rollBack();
            return false;
        }
        // 更新订单状态
        $result = $this->db->update(
            'foodcar_merchant_order',
            array('status' => 2, 'time_paid' => REQUEST_TIME),
            array('id IN(' . $item->order_id . ')')
        );
        if ($result === false) {
            $this->db->rollBack();
            return false;
        }
        return $this->db->commit();
    }
}