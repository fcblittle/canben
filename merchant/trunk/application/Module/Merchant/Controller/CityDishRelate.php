<?php 
/**
 * 添加 城市 菜品关联
 * 
 * @todo 添加成功后请 删除 此文件！
 */
namespace Module\Merchant\Controller;

use Application\Controller\Account;

class CityDishRelate extends Account {

    private $table = '`foodcar_official_dish`';
    private $tableCityRelated = '`foodcar_city_food`';

    public function _default()
    {
        // $dishes = $this->getDishes();

        // $relatedDishes = $this->getRelatedDish();

        $unrelatedDishes = $this->getUnrelatedDishes();
        if ($unrelatedDishes === false) {
            echo '获取不相关菜品失败！';die;
        }

        $result = $this->buildRelation($unrelatedDishes);
        if ($result === false) {
            echo '更新记录失败';
            var_dump($this->db->queryErrorInfo());
            die;
        }

        echo 'OK';
    }

    private function getUnrelatedDishes()
    {
        $sql = "SELECT dish.id, dish.food_name 
                FROM {$this->table} AS dish
                LEFT JOIN {$this->tableCityRelated} AS cityRelated
                ON cityRelated.food_id = dish.id
                WHERE cityRelated.id IS NULL";

        return $this->db->fetchAll($sql, array());
    }

    private function buildRelation($dishes)
    {
        if (empty($dishes)) {
            return true;
        }

        $commonFields = array(
            'city_id' => 1,
            'time_update' => time()
        );

        $records = array();
        foreach ($dishes as $item) {
            $records[] = array_merge($commonFields, array(
                'food_id' => $item->id
            ));
        }

        return $this->db->insert($this->tableCityRelated, $records);
    }
}