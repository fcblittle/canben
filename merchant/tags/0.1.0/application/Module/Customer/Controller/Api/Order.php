<?php 

namespace Module\Customer\Controller\Api;

use Application\Controller\AppApi;

/**
 * 订单相关API
 */
class Order extends AppApi {

    public function init() {
        $this->model = $this->model(':Order');
    }

    /**
     * 获取单个
     */
    public function getItem($args = array()) {
        $id = (int) $args['id'] ?: 0;
        $orderno = $args['orderno'] ?: '';
        $fields = $args['fields'] ?: '*';
        if (! $id && ! $orderno) {
            return $this->export(array(
                'code' => 400,
                'message' => 'Missing ID'
            ));
        }
        $item = $this->model->getItem(array(
            'id'      => $id,
            'orderno' => $orderno,
            'store_id' => $this->user->merchant_id,
            'fields'  => $fields
        ));
        $this->formatItem($item);

        return $this->export(array('content' => $item));
    }

    /**
     * 获取列表
     */
    public function getItemList($args = array()) {
        $args['pager'] = array(
            'page'  => $args['page'] ?: 0,
            'limit' => $args['limit'] ?: 15
        );
        $list = $this->model->getItemList($args);
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
     * 获取订单菜品
     */
    public function getDishes($args = array()) {
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
        $items = $this->model->getDishes($ids, $args['fields'] ?: 'od.*');
        if ($items === false) {
            return $this->export(array(
                'code' => 500,
                'message' => $this->db->sth->errorInfo()
            ));
        }
        return $this->export(array('content' => $items));
    }

    /**
     * 总数
     */
    public function total($args = array()) {
        $result = $this->model->getTotal($this->user->merchant_id);

        return $this->export(array('content' => $result));
    }

    /**
     * 按月统计
     * @param array $args
     * @return array
     */
    public function countMonthly($args = array()) {
        $month = trim($args['month']);
        if (! preg_match('#\d{6}#', $month)) {
            $this->export(array('code' => 400));
        }
        $data = array(
            'max'   => 0,
            'total' => 0,
            'list'  => array()
        );
        $month = $args['month'] ?: date('Ym', REQUEST_TIME);
        $nextMonthFirstDay = ($month + 1) . '01';
        $date = \DateTime::createFromFormat('Ymd', $nextMonthFirstDay);
        $maxDay = date('Ymd', $date->getTimestamp() - 86400);
        $result = $this->model->countMonthly($this->user->merchant_id, $month);
        for ($i = $month . '01'; $i < $maxDay + 1; $i++) {
            $count = 0;
            foreach ($result as $v) {
                if ($v->day == $i) {
                    $count = $v->count;
                    $data['total'] += $count;
                    $data['max'] = $data['max'] < $count ? $count : $data['max'];
                }
            }
            $data['list'][] = array((int) substr($i, -2), $count);
        }

        return $this->export(array('content' => $data));
    }

    /**
     * 格式化item
     *
     * @param $item
     * @return mixed
     */
    private function formatItem(& $item) {
        if ($item->insert_time) {
            $item->insert_time = date('Y-m-d H:i:s', $item->insert_time);
        }
        if ($item->order_expires_time) {
            $item->order_expires_time = date(
                'Y-m-d H:i:s',
                $item->order_expires_time
            );
        }
        if ($item->expect_arrival_time) {
            $item->expect_arrival_time = date(
                'Y-m-d H:i:s',
                $item->expect_arrival_time
            );
        }
    }
}