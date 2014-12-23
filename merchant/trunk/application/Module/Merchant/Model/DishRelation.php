<?php 

namespace Module\Merchant\Model;

use System\Model;

class DishRelation extends Model {

    private $tableDiner = '`foodcar_diner`';
    private $tableDish  = '`foodcar_official_dish`';
    private $tableDinerArea = '`foodcar_diner_area`';
    private $tableDinerCity = '`foodcar_diner_city`';
    private $tableCityFood  = '`foodcar_city_food`';
    
    public function __construct() {
        parent::__construct();
        $this->table = '`foodcar_food_relation`';
    }
    
    /**
     * 按id获取
     */
    public function getItemById($id, $uid, $fields = '*') {
        $sql = "SELECT {$fields} "
            . " FROM {$this->table} "
            . " WHERE id = ?"
            . " AND merchant_id = ?";
            
        return $this->db->fetch($sql, array($id, $uid));
    }

    /**
     * 获取全部
     */
    public function getAll($params = array()) {
        $params = array_merge(array(
            'fields'   => '*',
            'order'    => 'id DESC',
            'diner_id' => null,
            'status'   => null
        ), $params);
        $conds = '';
        $binds = array();
        if ($params['diner_id']) {
            $ids = is_array($params['diner_id'])
                ? implode(',', $params['diner_id'])
                : $params['diner_id'];
            $conds .= " AND diner_id IN ({$ids})";
        }
        if ($params['status']) {
            $conds .= " AND status = ?";
            $binds[]= $params['status'];
        }
        $sql = "SELECT {$params['fields']} "
            . " FROM {$this->table}"
            . " WHERE 1 {$conds}"
            . " ORDER BY {$params['order']}";

            // var_dump($binds);var_dump($sql);die;

        return $this->db->fetchAll($sql, $binds);
    }

    /**
     * 餐车 城市官方菜品
     */
    public function getCityDinerDish($args)
    {
        $binds = array();

        $params = array_merge(array(
            'fields'   => '*',
            'order'    => 'id DESC',
            'diner_id' => null
        ), $args);
        if ($params['diner_id']) {
            $ids = is_array($params['diner_id'])
                ? implode(',', $params['diner_id'])
                : $params['diner_id'];
            $conds .= " AND diner.id IN ({$ids})";
        }

        $sql = "SELECT {$params['fields']} 
                FROM {$this->tableDiner} AS diner
                LEFT JOIN {$this->tableDinerArea} AS area
                ON diner.area = area.id
                LEFT JOIN {$this->tableDinerCity} AS city
                ON area.city_id = city.id
                LEFT JOIN {$this->tableCityFood} AS cityfood
                ON city.id = cityfood.city_id
                LEFT JOIN {$this->tableDish} AS dish
                ON cityfood.food_id = dish.id
                WHERE 1 $conds";

        return $this->db->fetchAll($sql, $binds);
    }

    /**
     * 添加
     */
    public function add($data) {
        return $this->db->insert($this->table, $data);
    }
    
    /**
     * 更新
     */
    /*public function update($id, $uid, $data) {
        $ids = is_array($id) ? implode(',', $id) : $id;
        $conds = array(
            'id IN (' . $ids . ') AND merchant_id = ?',
            array($uid)
        );
        return $this->db->update($this->table, $data, $conds);
    }*/
    /**
     * 更新菜品状态
     */
    public function update($food, $dishes, $diner_id)
    {
        $diffDish = array_diff($dishes, $food);
        /*var_dump($dishes);
        var_dump($food);
        var_dump($diffDish);die;*/

        $this->db->beginTransaction();

        $foodIds = implode(',', $food);
        if (! empty($foodIds)) {

            $result = $this->db->update($this->table, 
                array('status' => 1), 
                array("food_id IN ({$foodIds}) AND diner_id = ?", array($diner_id))
            );
            if ($result === false) {
                $this->db->rollback();
                return false;
            }
        }

        $dishIds = implode(',', $diffDish);
        if (! empty($dishIds)) {

            $result = $this->db->update($this->table, 
                array('status' => 0), 
                array("food_id IN ({$dishIds}) AND diner_id = ?", array($diner_id))
            );
            if ($result === false) {
                $this->db->rollback();
                return false;
            }
        }

        return $this->db->commit();
    }
    
    /**
     * 删除
     */
    public function delete($ids, $uid) {
        if (is_array($ids)) {
            $ids = implode(',', $ids);
        }
        $sql = "DELETE FROM {$this->table}"
            . " WHERE id IN ({$ids})"
            . " AND merchant_id = {$uid}";
        return $this->db->execute($sql);
    }

    /**
     * 删除
     */
    public function deleteByRelation($uid, $type, $id) {
        $sql = "DELETE FROM {$this->table}"
            . " WHERE relation_type = ?"
            . " AND relation_id = ?"
            . " AND merchant_id = ?";

        return $this->db->execute($sql, array($type, $id, $uid));
    }

    /**
     * 查找商户采购订单中的菜品是否已存在
     */
    public function find($food_id,$diner_id)
    {
        $sql = "SELECT * FROM {$this->table} "
             . "WHERE `food_id` = ? "
             . "AND `diner_id`  = ? ";

        return $this->db->fetch($sql,array($food_id,$diner_id));
    }

    /**
     * 修改菜品sold_out
     */
    public function modify($id)
    {
        $sql = "UPDATE {$this->table} "
             . "SET `sold_out` = 0 "
             . "WHERE `id`  = ? ";

        return $this->db->execute($sql,array($id));
    }

    /**
     * 批量插入记录
     */
    public function addAll($add)
    {
        $sql = "INSERT INTO {$this->table}(food_id,diner_id,merchant_id,status,sold_out) "
             . "VALUES{$add} ";

        $result = $this->db->execute($sql);
        if ($result === false) {
                $this->db->rollback();
                return false;
            }
    }

}