<?php
/**
 * 银联信息返回处理接口
 * 
 * @abstract
 * @author kami <huany63@gmail.com>
 */
namespace Module\Fund\Controller;

use Application\Controller\Front;
use Application\Component\UnionPay;
use System\Component\Http\Request;

class CheckBack extends Front
{
    public function __construct()
    {
        parent::__construct();

        $this->model = $this->model(':Fund');
    }

    /**
     * 处理银行返回数据
     * 验证返回信息(订单号、金额)
     * 错误信息保存到表 table.foodcar_unionpay_consume_ck_error
     * 
     * @param method post
     */
    public function checkRechargeBack()
    {
        $response = new UnionPay\UnionPayService($_POST, UnionPay\UnionPayConfig::RESPONSE);
        if ($response->get('respCode') != UnionPay\UnionPayService::RESP_SUCCESS) {
            // 记录交易错误信息
            $errInfo = array(
                'code'    => $response->get('respCode'),
                'message' => $response->get('respMsg'),
                'retText' => json_encode($response->get_args()),
                'created' => REQUEST_TIME
            );

            $this->model->addConsumeErrorRecord($errInfo);

            return;
        }
        // 核对订单号 及 订单总额
        $retVal = $response->get_args();
        $order = $this->model->getConsumeRecord(array(
            'fields'    => 'id, uid, role, order_num, qid, amount, time_start',
            'order_num' => $retVal['orderNumber'],
        ));
        if ($order === false) {
            $errInfo = array(
                'code'    => 'INVALID_ORDER_NUM',
                'message' => '不存在此订单！',
                'retText' => json_encode($retVal),
                'created' => REQUEST_TIME
            );
        } else if ($retVal['orderAmount'] != $order->amount * 100) {
            $errInfo = array(
                'code'    => 'INCORRECT_ORDER_AMOUNT',
                'message' => '充值金额错误！充值金额：' . $order->amount,
                'retText' => json_encode($retVal),
                'created' => REQUEST_TIME
            );
        }
        ! empty($errInfo) && $this->model->addConsumeErrorRecord($errInfo);

        if((empty($errInfo) || $errInfo['code'] == 'INCORRECT_ORDER_AMOUNT') 
                && empty($order->qid) && $this->model->setRetStatus($order, $retVal))
        {
            echo $retVal['respMsg'];
        }
    }
}