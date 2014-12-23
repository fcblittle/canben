<?php

namespace Module\Fund\Controller;

use Application\Controller\Account;


class Purchase extends Account
{
    public function __construct()
    {
        parent::__construct();

        if(! $this->checkMerchantPermission()) 
        {
            $this->error('您无此权限！');
        }
    }
    
    public function index()
    {
        $_GET['start']=$_GET['start']?:date("Y-m-d",time());

        $_GET['payStatus']=$_GET['payStatus']?:'-1';
        if($_GET['payStatus'] == '1')
        {
            $_GET['payStatus'] = '0';
        }
        
        $_GET['diner_id']=$_GET['diner_id']?:-1;
        
        $bl=$this->call('api/fund/balance/getBalance');
        if ($bl['code'] != 200) {
            $this->message->set($result['message'], 'error');
        }
        
        $dinerOrder=array();
        
        $result = $this->call(
            'api/merchant/diningcar/getItemList'
            );
        if ($result === false) {
            return false;
        }   
        $diners = $result['content']['list'];
        $dinerIds = array();
        $diner_names = array();
        $dailyTotal = array();
        foreach ($diners as $item) 
        {
            $dinerIds[] = $item->id;
            $diner_names[$item->id] = $item->diner_name;
        }
        
        $data = $this->call('api/fund/purchase/getItems',
                             array(
                                    'start' => $_GET['start'] . " 00:00:00",
                                    'end' => $_GET['start'] . " 24:00:00",
                                    'status' => $_GET['payStatus'],
                                    'diner_id' => $_GET['diner_id']
                            ));
        if ($data['code'] != 200) {
            $this->message->set($result['message'], 'error');
        }
        
        foreach($data['content'] as $key => $item)
        {
            if($item->status != 1 && $item->status != 4 && $item->status !=3)
            {
                $dailyTotal[$item->diner_id]['diner_name'] = $item->diner_name;
                $dailyTotal[$item->diner_id]['realname'] = $item->realname;
                $dailyTotal[$item->diner_id]['amount'] += $item->total_price;
                
            }
        }
    
        if($dailyTotal)
        {
            foreach($dailyTotal as $key => $value)
            {
                $dailyTotal[$key]['status'] = 1;
            }
        }
        
        foreach($data['content'] as $key => $item)
        {
            if($item->status != 1 && $item->status != 4)
            {
                $dailyTotal[$item->diner_id]['status'] &= ($item->status == 2);
            }
        }
        
        $today = date("Y-m-d",time());
        $see = strtotime(date("Y-m-d 16:00:00",time()));
        $now = time();
        
        $this->view->dailyTotal = $dailyTotal;


        $this->view->balance=$bl['content'];
        $this->view->diner_names = $diner_names;
        $this->view->render(':purchase.index');
    
    
    }

    public function pay()
    {
        // 获取参数
        $params = $_POST;
        $res = 1;       
        $result = ! empty($params['validate']) ? true : $this->checkShippingDate($params);
        if ($result !== true) {
            echo json_encode($result);die;
        }
        if($params)
        {           
            //判断是否为批量支付
            if($params['ids'])
            {
                $allOrders = $this->call('api/fund/purchase/getItems',
                    array(
                        'start' => $params['start'] . " 00:00:00",
                        'end' => $params['start'] . " 24:00:00",
                        'status' => 0,
                        'diner_ids' => $params['ids']
                    )
                );
                $relations = $allOrders['content'];
                $amount = 0;
                if($allOrders['code'] ==200)
                {
                    foreach ($allOrders['content'] as $item) 
                    {
                        $amount += $item->total_price;
                    }
                }
                $pars = array(
                    'variationType' => 16,
                    'amount' => floatval($amount),
                    'start' => $params['start']
                );
                
                //用账户余额进行支付
                $data = $this->call('api/fund/fund/transferAccounts',$pars);
                
                if ($data === false) {
                    return $this->export(array(
                        'code' => 500,
                        'message' => $this->db->sth->errorInfo()
                    ));
                }
                //支付成功，修改记录
                if($data['code'] == 200)
                {
                    foreach ($params['ids'] as $key => $value)
                    {
                        $pars['diner_id'] = intval($params['ids'][$key]);
                        
                        $orders = $this->call('api/fund/purchase/getItems',
                            array(
                                    'start' => $params['start'] . " 00:00:00",
                                    'end' => $params['start'] . " 24:00:00",
                                    'status' => '0',
                                    'diner_id' => $pars['diner_id']
                                )
                            );
                        $order_ids = '';
                        if($orders['code'] ==200)
                        {
                            foreach ($orders['content'] as $item) 
                            {
                                $order_ids .= $item->id.',';
                            }
                        }
                    
                        if($order_ids)
                        {
                            $order_ids = rtrim($order_ids,',');
                            $args = array('ids' => $order_ids);
                        }
                        
                        if($args)
                        {
                            $result = $this->call('api/fund/purchase/update',$args);
                        }
                        
                        if($result['code'] == 200)
                        {
                            $shipping = $this->call('api/fund/shipping/createShippingTime',
                            array('order_id' => $order_ids));
                        }   
                        
                        if($shipping['code'] == 200)
                        {
                            $args['time_send'] = $shipping['shipping_time'];
                        }
                        if($args['time_send'])
                        {
                            $send = $this->call('api/fund/purchase/send',$args);
                        }
                        
                        $res &= $send['content'];
                        //判断批量支付是否成功
                        if(!$res)
                        {
                            $msg = $send['message'];
                        }
                            
                            
                    }

                    if($res)
                    {
                        echo '支付成功！';
                        //更新餐车菜品表foodcar_food_relation
                        $this->call("api/merchant/DishRelation/relate",$relations);
                    } else {
                        echo $msg;
                    }
               }                            
            }   
            else //单个餐车支付
            {
                
                $params['variationType'] = 16;
                $params['diner_id'] = intval($params['diner_id']);
                $orders = $this->call('api/fund/purchase/getItems',
                            array(
                                    'start' => $params['start'] . " 00:00:00",
                                    'end' => $params['start'] . " 24:00:00",
                                    'status' => '0',
                                    'diner_id' => $params['diner_id']
                                )
                            );             
                $relations = $orders['content'];
                /*//更新餐车菜品表foodcar_food_relation
                $this->call("api/merchant/DishRelation/relate",$relations);die;*/
                $order_ids = '';
                $amount = 0;
                if($orders['code'] == 200)
                {
                    foreach ($orders['content'] as $item) 
                    {
                        $order_ids.= $item->id.',';
                        $amount += $item->total_price;
                    }
                }
                $params['amount'] = floatval($amount);
                $data = $this->call('api/fund/fund/transferAccounts',$params);                
                if ($data === false) {
                    return $this->export(array(
                        'code' => 500,
                        'message' => $this->db->sth->errorInfo()
                    ));
                }
                if($data)
                {
                    if($order_ids)
                    {
                        $order_ids = rtrim($order_ids,',');
                        $args = array('ids' => $order_ids);
                    }                   
                    if($args)
                    {
                        $result = $this->call('api/fund/purchase/update',$args);
                    }                   
                    if($result['code'] == 200)
                    {
                        $shipping = $this->call('api/fund/shipping/createShippingTime',
                        array('order_id' => $order_ids));
                    }                   
                    if($shipping['code'] == 200)
                    {
                        $args['time_send'] = $shipping['shipping_time'];
                    }
                    if($args['time_send'])
                    {
                        $send = $this->call('api/fund/purchase/send',$args);
                    }                   
                    if($send['code'] == 200)
                    {
                        echo '支付成功！';
                        //更新餐车菜品表foodcar_food_relation
                        $this->call("api/merchant/DishRelation/relate",$relations);
                    }
                    else
                    {
                        echo '支付失败！';
                    }       
                }
            }
        }
    }
        
    private function checkShippingDate($args = array())
    {
        $orders = $this->call('api/fund/purchase/getItems',
            array(
                'start' => $args['start'] . " 00:00:00",
                'end'   => $args['start'] . " 24:00:00",
                'status'    => 0,
                'diner_ids' => $args['ids'] ?: array($args['diner_id'])
            )
        );

        $messages = array();
        foreach ($orders['content'] as $item) {
            $shipping = $this->call(
                'api/fund/shipping/createShippingTime',
                array('order_id' => $item->id)
            );
            
            if ($item->time_send != $shipping['shipping_time']) {
                $message = array();
                $message[] = '官方送货日期与所选送货日期不同，确认继续？';
                $message[] = '订单id：<span style="font-weight: bold;">' . $item->id . '</span>';
                $message[] = '所属餐车：<span style="font-weight: bold;">' . $item->diner_name . '</span>';
                $message[] = '下单时间：<span style="font-weight: bold;">' . date('Y-m-d', $item->created) . '</span>';
                $message[] = '用户所选送货时间：<span style="font-weight: bold;">' . date('Y-m-d', $item->time_send) . '</span>';
                $message[] = '系统分配送货时间：<span style="font-weight: bold;">' . date('Y-m-d', $shipping['shipping_time']) . '</span>';

                $messages[] = implode('<br />', $message);
            }
        }

        return ! empty($messages) 
                ? array(
                    'code'    => 'PURCHASE.NOT_MATCH_SHIPPING_TIME',
                    'message' => implode('<br /><br />', $messages)
                ) : true;
    }

    

}