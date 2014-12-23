<?php

namespace Module\Fund\Model;

use System\Model;

class Balance extends Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = '`foodcar_merchant_fund`';
    }

    /**
     * 获取该商户钱包和账户余额
     */
    public function getBalance($args)
    {
        $params = array_merge(array(
            'fields'      => '*',
            'merchantId'  => null
        ), $args);
    
        $conds = '';
        $binds = array();
    
        if ($params['merchantId'] != null) {
            $conds .= " AND merchant_id=?";
            $binds[] = $params['merchantId'];
        }
    
        $sql = "SELECT {$params['fields']}
        FROM {$this->table}
        WHERE 1 $conds";
    
        return $this->db->fetch($sql, $binds);
    }
}