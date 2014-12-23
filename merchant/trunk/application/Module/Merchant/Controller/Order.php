<?php 

namespace Module\Merchant\Controller;

use Application\Controller\Account;

class Order extends Account {

    /**
     * 支付完成
     */
    public function paid() {
        $data = $_POST;
        if ($data['v_pstatus'] == 20) {
            // 获取支付信息
            $modelPayment = $this->model(':Payment');
            $item = $modelPayment->getItem(array(
                'id' => $data['v_oid'],
                'merchant_id' => $data['remark1']
            ));
            // 更新支付信息
            if ($item && $item->status == 0) {
                $result = $this->updateStatus($data, $item);
            }
        }
        $this->view->result = $_POST;
        $this->view->render(':order.paid');
    }

    /**
     * 更新支付状态
     * @param $data
     * @param $item
     * @return bool
     */
    private function updateStatus($data, $item) {
        $modelPayment = $this->model(':Payment');
        $this->db->beginTransaction();
        $result = $modelPayment->update(
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