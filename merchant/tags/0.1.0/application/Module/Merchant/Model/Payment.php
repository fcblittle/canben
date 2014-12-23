<?php 

namespace Module\Merchant\Model;

use System\Model;

class Payment extends Model {

    private $table = '`foodcar_merchant_payment`';

    /**
     * 添加
     */
    public function add($data) {
        return $this->db->insert($this->table, $data);
    }

    public function getItem($params) {
        $params = array_merge(array(
            'id'      => '',
            'merchant_id' => 0,
            'fields'  => '*'
        ), $params);
        $conds = '';
        $binds = array($params['merchant_id']);
        if ($params['id']) {
            $conds .= ' AND id = ?';
            $binds[] = $params['id'];
        }

        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->table} "
            . " WHERE merchant_id = ? {$conds}";

        return $this->db->fetch($sql, $binds);
    }

    public function update($data, $conds) {
        return $this->db->update($this->table, $data, $conds);
    }
   
}