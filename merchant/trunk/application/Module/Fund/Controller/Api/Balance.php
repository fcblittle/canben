<?php

namespace Module\Fund\Controller\Api;

use Application\Controller\AppApi;

class Balance extends AppApi
{
    public function init()
    {
        $this->model = $this->model(':Balance');
    }
    
    //获取钱包和账户余额
    public function getBalance()
    {
        $params=array('merchantId'  => $this->user->id);
    
        $result = $this->model->getBalance($params);
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
}