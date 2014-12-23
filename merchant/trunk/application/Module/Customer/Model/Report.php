<?php 

namespace Module\Customer\Model;

use System\Model;

/**
 * 退款模型
 * Class Refund
 * @package Module\Merchant\Model
 */
class Report extends Model {

    private $table = '`foodcar_order_offline`';

    /**
     * 根据餐车id获取所有提报
     */
    public function getReports($ids, $date, $page, $limit) {
        $start = $page * $limit;
        if(empty($date)) {
            $sql = "SELECT a.diner_id,a.created,SUM(a.count * b.sale_price) as sumPrice,c.diner_name FROM {$this->table} as a
            LEFT JOIN foodcar_official_dish as b ON a.dish_reversion_id = b.revision_id
            LEFT JOIN foodcar_diner as c ON a.diner_id = c.id
            WHERE a.diner_id IN {$ids} GROUP BY a.diner_id,a.created ORDER BY a.id DESC LIMIT {$start}, {$limit}";
        } else {
            $month = substr($date, 0, 2);
            $day = substr($date, 3, 2);
            $year = substr($date, 6, 4);
            echo $year.$month.$day;
            $begin = mktime(0, 0, 0, $month, $day, $year);
            $end = mktime(23, 59, 59, $month, $day, $year);
            $sql = "SELECT a.diner_id,a.created,SUM(a.count * b.sale_price) as sumPrice,c.diner_name FROM {$this->table} as a
            LEFT JOIN foodcar_official_dish as b ON a.dish_reversion_id = b.revision_id 
            LEFT JOIN foodcar_diner as c ON a.diner_id = c.id 
            WHERE a.diner_id IN {$ids} AND a.created BETWEEN {$begin} AND {$end} GROUP BY a.diner_id,a.created ORDER BY a.id DESC LIMIT {$start}, {$limit}";
        }
        return $this->db->fetchAll($sql, '');
    }
    
    /**
     * 根据餐车id和餐车名称获取所有提报
     */
    public function getReportsByName($ids, $name, $date, $page, $limit) {
        $start = $page * $limit;
        if(empty($date)) {
            $sql = "SELECT a.diner_id,a.created,SUM(a.count * b.sale_price) as sumPrice,c.diner_name FROM {$this->table} as a
            LEFT JOIN foodcar_official_dish as b ON a.dish_reversion_id = b.revision_id
            LEFT JOIN foodcar_diner as c ON a.diner_id = c.id
            WHERE a.diner_id IN {$ids} AND c.diner_name LIKE '%{$name}%' GROUP BY a.diner_id,a.created ORDER BY a.id DESC LIMIT {$start}, {$limit}";
        } else {
        $year = substr($date, 0, 4);
        $month = substr($date, 4, 2);
        $day = substr($date, 6, 2);
        $begin = mktime(0, 0, 0, $month, $day, $year);
        $end = mktime(23, 59, 59, $month, $day, $year);
        $sql = "SELECT a.diner_id,a.created,SUM(a.count * b.sale_price) as sumPrice,c.diner_name FROM {$this->table} as a
        LEFT JOIN foodcar_official_dish as b ON a.dish_reversion_id = b.revision_id 
        LEFT JOIN foodcar_diner as c ON a.diner_id = c.id
        WHERE a.diner_id IN {$ids} AND c.diner_name LIKE '%{$name}%' AND a.created BETWEEN {$begin} AND {$end} GROUP BY a.diner_id,a.created ORDER BY a.id DESC LIMIT {$start}, {$limit}";
        }
        return $this->db->fetchAll($sql, '');
        }
    
    /**
     * 根据餐车id和日期获取提报
     */
    public function getReport($id, $date) {
        $sql = "SELECT a.*,b.sale_price,b.food_name FROM {$this->table} as a
        LEFT JOIN foodcar_official_dish as b ON a.dish_reversion_id = b.revision_id WHERE a.diner_id = {$id} AND a.created = {$date} ORDER BY a.id DESC";
        return $this->db->fetchAll($sql, '');
    }
    
    /**
     * 获取总数
     */
    public function getTotalNum($ids) {
        $sql = "SELECT count(distinct created) as num FROM {$this->table}   
        WHERE diner_id IN {$ids}";
        return $this->db->fetch($sql, '');
    }
    
    /**
     * 根据商户id查找所有餐车id
     */
    public function getDinerIds($id) {
        $sql = "SELECT distinct diner_id FROM biz_staff WHERE merchant_id = {$id};";
        return $this->db->fetchAll($sql, '');
    }
    
    /**
     * 根据经营者id查找对应餐车id
     */
    public function getDinerId($id) {
        $sql = "SELECT diner_id FROM biz_staff WHERE merchant_id = {$id};";
        return $this->db->fetch($sql, '');
    }
    
    /**
     * 根据经营者id查找对应餐车id
     */
    public function getRecentTime() {
        $sql = "SELECT max(created) as time FROM {$this->table}";
        return $this->db->fetch($sql, '');
    }
    
    /**
     * 获取所有餐品信息
     */
    public function getDishes() {
        $sql = "SELECT id,food_name,revision_id,sale_price FROM  foodcar_official_dish GROUP BY food_name ORDER BY id DESC;";
        return $this->db->fetchAll($sql, '');
    }
}