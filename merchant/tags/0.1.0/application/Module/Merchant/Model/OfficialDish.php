<?php 

namespace Module\Merchant\Model;

use System\Model;

class OfficialDish extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = '`foodcar_official_dish`';
    }

    /**
     * 获取多个item
     *
     * @param $id
     * @param $uid
     * @param string $fields
     * @return mixed
     */
    public  function getItems($id, $fields = '*') {
        $id = is_array($id) ? implode(',', $id) : $id;
        $sql = "SELECT {$fields} FROM {$this->table}"
            . " WHERE id IN({$id})";

        return $this->db->fetchAll($sql);
    }

    /**
     * 获取全部
     */
    public function getAll($params = array()) {
        $params = array_merge(array(
            'fields'  => '*',
            'order'   => 'id DESC',
        ), $params);
        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->table}"
            . " WHERE foodstatus = 1"
            . " ORDER BY {$params['order']}";

        return $this->db->fetchAll($sql);
    }
}