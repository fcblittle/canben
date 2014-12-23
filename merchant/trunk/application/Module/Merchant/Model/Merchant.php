<?php 

namespace Module\Merchant\Model;

use System\Model;

class Merchant extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = '`foodcar_merchant`';
    }

    /**
     * 按username获取
     */
    public function getItemByName($username, $fields = '*') {
        $sql = "SELECT {$fields} "
            . " FROM {$this->table} "
            . " WHERE merchant_login = ?"
            . " LIMIT 1";

        return $this->db->fetch($sql, array($username));
    }

    /**
     * 通过id获取商户
     */
    public function getItemById($merchant_id, $fields = '*')
    {
        $sql = "SELECT {$fields}"
                ." FROM {$this->table}"
                ." WHERE id = ?"
                ." LIMIT 1";

        return $this->db->fetch($sql, array($merchant_id));
    }
}