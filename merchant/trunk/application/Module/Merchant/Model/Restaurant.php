<?php 

namespace Module\Merchant\Model;

use System\Model;

class Restaurant extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = '`foodcar_store`';
    }
    
    /**
     * 获取单条记录
     * 
     * @param int $id
     * @param string $fields
     */
    public function getItem($params) {
        $params = array_merge(array(
            'id' => 0,
            'merchant_id' => 0,
            'fields' => '*'
        ), $params);
        $conds = '';
        $binds = array();
        if ($params['id']) {
            $conds .= " AND id = ?";
            $binds[] = $params['id'];
        }
        if ($params['merchant_id']) {
            $conds .= " AND merchant_id = ?";
            $binds[] = $params['merchant_id'];
        }
        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->table}"
            . " WHERE 1 {$conds}";
        $item = $this->db->fetch($sql, $binds);

        return $item;
    }

    /**
     * 添加
     *
     * @param $data
     * @return mixed
     */
    public function add($data) {
        return $this->db->insert($this->table, $data);
    }

    /**
     * 更新餐厅
     * 
     * @param int $id
     * @param array $data
     */
    public function update($id, $data) {
        return $this->db->update($this->table, $data, array('merchant_id = ' . $id));
    }

}