<?php

namespace Module\Customer\Controller\Api;

use Application\Controller\AppApi;

class OrderOffline extends AppApi
{
    public function init()
    {
        $this->model = $this->model(':Order');
    }

    public function getItem($args)
    {
        $params = array_merge(array(
            'diner_id'   => null,
            'order_date' => date('Y-m-d', time()),
        ),$args);

        $params = array_merge($params, $this->getDateInterval($params['order_date']));
        $result = $this->model->getOrderOffline($params);
        if ($result === false) {
            return $this->export(array(
                'code' => 500
            ));
        }

        return $this->export(array(
            'code'    => 200,
            'content' => $result
        ));
    }

    public function getItemList($args = array())
    {
        $params = array_merge(array(
            'diner_id'   => null,
            'order_date' => null
        ),$args);

        $this->model->getOrderOffline($params);
    }

    /**
     * 获取时间区间
     */
    private function getDateInterval($date)
    {
        if (is_array($date)) {
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
                $end = mktime(0, 0, 0, $month, $day + 1, $year);
            } else {
                list($year, $month, $day) = explode('-', $cur_date);
                $end = mktime(0, 0, 0, $month, $day + 1, $year);
            }
        } else {
            list($year, $month, $day) = explode('-', $date);
            $start = mktime(0, 0, 0, $month, $day, $year);
            $end = mktime(0, 0, 0, $month, $day + 1, $year);
        }

        return array(
            'order_date_cur'  => $start,
            'order_date_next' => $end
        );
    }
}