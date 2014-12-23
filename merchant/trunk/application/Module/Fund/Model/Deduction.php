<?php

namespace Module\Fund\Model;

use System\Model;

class Deduction extends Fund
{
    private $tableShipping = '`foodcar_diner_shipping`';
    private $tableDiner    = '`foodcar_diner`';

    public function __construct()
    {
        parent::__construct();
    }

    public function shipping($data)
    {
        $this->db->beginTransaction();
        // 扣款
        foreach ($data as $item) {
            // 
            $result = $this->doTransfer(array(
                'role'            => 'merchant',
                'uid'             => $this->getDinerMerchantId($item->diner_id),
                'diner_id'        => $item->diner_id,
                'variationType'   => 23,
                'accountType'     => 'account',
                'accountTo'       => 'official',
                'roleAccountType' => 'merchant',
                'roleAccountTo'   => 'official',
                'amount'          => $item->amount,
                'created'         => $item->created
            ), '-');
            if ($result === false) {
                $this->db->rollback();
                return false;
            }
        }

        // 新增记录
        $result = $this->db->insert($this->tableShipping, $data);
        if ($result === false) {
            $this->db->rollback();
            return false;
        }

        return $this->db->commit();
    }

    private function getDinerMerchantId($dinerId)
    {
        $sql = "SELECT merchant_id FROM {$this->tableDiner} WHERE id = ?";

        return $this->db->fetch($sql, array($dinerId))->merchant_id;
    }
}