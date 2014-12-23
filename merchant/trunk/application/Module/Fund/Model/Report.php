<?php

namespace Module\Fund\Model;

use System\Model;

class Report extends Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = '`foodcar_order_offline`';
       
    }


    /**
     * 取得列表
     */
    public function getItemList($params = array())
    {
        $params = array_merge(array(
            'fields'    => '*',
            'order'     => 'created DESC',
            'diner_id'  => null,
            'start' => 0,
            'end' => time(),
            'keyword' => '', 
        ), $params);
        $dinerIds = is_array($params['diner_id'])
            ? implode(',', $params['diner_id'])
            : $params['diner_id'];

        $cond = " AND created BETWEEN {$params['start']} AND {$params['end']}"
            . " AND diner_name LIKE '%{$params['keyword']}%'";

        $sql = "SELECT {$params['fields']}"
            . " FROM {$this->table} AS a"
            . " INNER JOIN `foodcar_official_dish_revision` AS b ON a.dish_reversion_id = b.id"
            . " INNER JOIN `foodcar_diner` AS c ON a.diner_id = c.id"
            . " WHERE diner_id IN ({$dinerIds}) {$cond}"
            . " ORDER BY {$params['order']}";


        return array("list" => $this->db->fetchAll($sql));
    }   

    /**
     * 判断当前日期某餐车是否有提报(manager)
     */
    public function isExisting($dinerId)
    {
        $t = time();
        /*$start = floor($t/86400)*86400-28800;
        $end = ceil($t/86400)*86400-28800-1;*/
        $start = mktime(0, 0, 0, date('m', $t), date('d', $t), date('Y', $t));
        $end = mktime(0, 0, 0, date('m', $t), date('d', $t) + 1, date('Y', $t)) - 1;
        $sql = "SELECT * FROM {$this->table} WHERE diner_id = {$dinerId} AND created BETWEEN {$start} AND {$end}";
        return $this->db->fetch($sql);
    }

    /**
     * 获取某日期(时间戳)某餐车的线上销售额
     */
    public function getOnlineByTimeAndDinerId($t, $dinerId)
    {
 /*       $start = floor($t/86400)*86400-28800;
        $end = ceil($t/86400)*86400-28800-1;*/
        $start = mktime(0, 0, 0, date('m', $t), date('d', $t), date('Y', $t));
        $end = mktime(0, 0, 0, date('m', $t), date('d', $t) + 1, date('Y', $t)) - 1;
        $sql = "SELECT SUM(order_amount) AS sum FROM `foodcar_order` WHERE store_id = {$dinerId} AND insert_time BETWEEN {$start} AND {$end}";
        return $this->db->fetch($sql)->sum;
    }

    /**
     * 获取餐车菜品
     */
    public function getItemByDinerId($dinerId)
    {
        $sql = <<<EOF
    SELECT * FROM `foodcar_food_relation` AS a
    INNER JOIN `foodcar_official_dish` AS b ON a.food_id = b.id
    WHERE a.diner_id = {$dinerId} AND a.status = 1
EOF;
        return $this->db->fetchAll($sql);
    }

    /**
     * 添加
     */
    public function add($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * 转账（将transfered从0改为当前的时间戳）
     */
    public function transfer($diner_id, $start, $end)
    {
        $time = time();
        $sql = "UPDATE {$this->table}
                SET `transfered` = {$time}
                WHERE `diner_id` = {$diner_id}
                AND `created` BETWEEN {$start} AND {$end}";

        
        return $this->db->execute($sql);
    }

    /**
     * 删除
     */
    public function delete($ids)
    {
        $ids = implode(',', $ids);

        $sql = "DELETE FROM {$this->table} WHERE id IN($ids)";

        return $this->db->execute($sql, array());
    }
}