<?php

namespace Module\Fund\Controller\Api;

use Application\Controller\AppApi;

class Shipping extends AppApi
{
    public $beginning = 0;//发货的起始日期（时间戳）
    public $interval = 2;//发货的间隔日期（天数）
    public $deadline = 0;//下一个发货日发货的截止时间（时间戳）
    public $deadlineHour = 18;//下一个发货日发货的截止时间的小时数，发货截止时间为发货日的18:00
    public $shippingHour = 8;//发货的默认时间的小时数(H),默认为08:00发货

    public function getParams($args = array())
    {
        $params = array();

        unset($args['export']);

        foreach ($args as $v) {
            $params[$v] = $this->$v;
        }

        return $params;
    }

    public function setParams($args = array())
    {
        if (! is_array($args)) {
            return false;
        }
        foreach ($args as $key => $v) {
            if (! isset($this->$key)) {
                continue;
            }
            $this->$key = $v;
        }
    }

    public function getBeginningDay()
    {
        return mktime($this->shippingHour, 0, 0, 10, 1, 2014);
    }
        
    public function createShippingTime($args)
    {
        $this->beginning =  $this->getBeginningDay();

        $result = $this->call(
            'api/merchant/merchantOrder/getItems',
            array(
                'ids'    => $args['order_id'],
                'fields' => 'id,status,time_send'
            )
        );
        //var_dump($result);
        if ($result['code'] != 200) {
            return $this->export(array(
                    'code' => 400,
                    'message' => $result['message']
                ));
        }

        $orders = $result["content"];
        if (empty($orders)) {
            return $this->export(array(
                    'code' => 500,
                    'message' => "未提交有效支付订单"
                ));
        }
        $shipping_time = $this->checkTime($orders);
        // var_dump($shipping_time);
        if($shipping_time)
        {
            return $this->export(array(
            'code' => 200,
            'shipping_time' => $shipping_time
            ));
        }
        
        
    }

    private function checkTime($orders)
    {
        $time_paid = REQUEST_TIME;
        // 用户所选送货时间
        $item = array_pop($orders);
        $time_send = $item->time_send;
        // 判断支付日期是否在发货日
        $temp = mktime(
            $this->shippingHour, 0, 0, 
            date("n", $time_paid), 
            date("j", $time_paid),
            date("Y", $time_paid)
        );

        $space = ($temp - $this->beginning) % ($this->interval * 24 * 3600);
        $period = ($temp - $this->beginning) / ($this->interval * 24 * 3600);

        if ($space == 0) {
            // 支付日期为发货日
            $this->deadline = mktime(
                $this->deadlineHour, 0, 0, 
                date("n", $time_paid), 
                date("j", $time_paid),
                date("Y", $time_paid)
            );

            // 判断支付时间是否在截止日期前面
            if ($time_paid <= $this->deadline) {
                $shipping_time = $temp + $this->interval * 24 * 3600;
            } else {
                $shipping_time = $temp + $this->interval * 24 * 3600 * 2;
            }
        } else {
            // 支付日期不是发货日
            // $shipping_time = $this->beginning + ($this->interval * 24 * 3600) * (2 + floor(($temp - $this->beginning) / ($this->interval * 24 * 3600)));
            // $shipping_time = (($this->interval * 24 * 3600) - $space) + $temp;
            $shipping_time = $this->beginning + $this->interval * (floor($period) + 1) * 24 * 3600;
        }

        if ($time_send > $shipping_time && 
            (($time_send - $this->beginning) % ($this->interval * 24 * 3600) == 0)
        ) {
            return $time_send;
        }

        return $shipping_time;
    }

    
}