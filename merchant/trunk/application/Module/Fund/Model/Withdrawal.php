<?php

namespace Module\Fund\Model;

use System\Model;

class Withdrawal extends Model
{
    private $table = '`foodcar_merchant_withdrawals`';

    public function __construct()
    {
        parent::__construct();
    }

    public function getItem($args)
    {
        $binds = array();

        $params = array_merge(array(
            'fields' => '*',
        ), $args);

        $conds = " AND id = ?";
        $binds[] = $args['id'];

        $sql = "SELECT {$params['fields']} 
                FROM {$this->table} 
                WHERE 1 $conds";

        return $this->db->fetch($sql, $binds);
    }

    public function pass($args)
    {
        // 
    }

    public function update($data, $id)
    {
        return $this->db->update(
            $this->table, 
            $data, 
            array('id = ' . $id)
        );
    }
}