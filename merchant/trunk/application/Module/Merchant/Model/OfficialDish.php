<?php 

namespace Module\Merchant\Model;

use System\Model;

class OfficialDish extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = '`foodcar_official_dish`';
        $this->city = '`foodcar_city_food`';
    }

    /**
     * 获取城市菜品ids
     *
     */
    public  function getCityDish($args) {
        $binds[] = $args['city_id'];
        $sql = "SELECT {$args['fields']} FROM {$this->city}"
            . " WHERE city_id = ?";
        return $this->db->fetchAll($sql,$binds);
    }

    /**
     * 获取某城市下菜品
     *
     * @param $id
     * @param $uid
     * @param string $fields
     * @return mixed
     */
    public  function getCityItems($id, $fields = '*') {
        $id = is_array($id) ? implode(',', $id) : $id;
        $sql = "SELECT {$fields} FROM {$this->table}"
            . " WHERE id IN({$id})";

        return $this->db->fetchAll($sql);
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

    /**
     * 获取历史版本
     */
    public function getDishRevision($args)
    {
        $params = array_merge(array(
            'fields' => '*'
        ),$args);

        if (is_array($params['id'])) {
            $id = implode(',', $params['id']);
        } else {
            $id = $params['id'];
        }

        $sql = "SELECT {$params['fields']} 
                FROM `foodcar_official_dish_revision`
                WHERE id IN ($id)";

        return $this->db->fetchAll($sql, array());
    }
}