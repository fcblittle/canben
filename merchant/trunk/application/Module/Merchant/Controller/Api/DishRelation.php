<?php 

namespace Module\Merchant\Controller\Api;

use Application\Controller\AppApi;

/**
 * 菜品关联API
 */
class DishRelation extends AppApi {

    private $model = null;

    public function init() {
        $this->model = $this->model(':DishRelation');
    }

    public function permission() {
        return array(
            'public' => array('autoUpdate')
        );
    }
    
    /**
     * 获取菜品
     */
    public function getItem($args = array()) {
        $id = (int) $args['id'];
        $fields = $args['fields'] ?: '*';
        if (! $id) {
            return $this->export(array(
                'code' => 400, 
                'message' => 'Missing ID'
            ));
        }
        $item = $this->model->getItemById($id, $this->user->id, $fields);
        $this->formatItem($item);
        
        return $this->export($item);
    }

    /**
     * 获取全部
     */
    public function getAll($args = array()) {
        $params = array_merge(array(
            'fields'   => $args['fields'] ?: '*',
            'diner_id' => null,
            'status'   => null
        ), $args);

        $items = $this->model->getAll($params);

        return $this->export(array('content' => $items));
    }

    /**
     * 获取餐车 官方城市菜品
     */
    public function getCityDinerDish($args)
    {
        $dishes = $this->model->getCityDinerDish(array(
            'fields'   => $args['fields'] ?: '*',
            'diner_id' => (int) $args['diner_id'],
        ));

        return $this->export(array('content' => $dishes));
    }

    /**
     * 添加
     */
    public function add($args = array()) {
        $data = $this->prepareData($args);
        $id = $this->model->add($data);

        return $this->export($id);
    }

    /**
     * 确认订单后自动更新菜品关联
     */
    public function autoUpdate() {
        $return = array();
        $start = new \DateTime(date('Y-m-d'));
        $end = new \DateTime(date('Y-m-d'));
        $end = $end->setDate($start->format('Y'), $end->format('m'), (int) $end->format('d') + 1);
        $binds = array($start->getTimestamp(), $end->getTimestamp());
        // 获取餐车
        $sql = "SELECT DISTINCT(diner_id),merchant_id"
            . " FROM `foodcar_merchant_order`"
            . " WHERE status = 3"
            . " AND time_confirmed >= ?"
            . " AND time_confirmed <= ?";
        $cars = $this->db->fetchAll($sql, $binds);
        foreach ($cars as $v) {
            $updates = $inserts = array();
            // 获取订单中的菜品
            $sql = "SELECT DISTINCT(a.dish_id) AS dishId"
                . " FROM `foodcar_merchant_order_detail` a"
                . " LEFT JOIN `foodcar_merchant_order` b"
                . " ON a.order_id = b.id"
                . " WHERE b.diner_id = ?"
                . " AND b.status = 3"
                . " AND b.time_confirmed >= ?"
                . " AND b.time_confirmed <= ?";
            $binds = array(
                $v->diner_id,
                $start->getTimestamp(),
                $end->getTimestamp(),
            );
            $ordered = $this->db->fetchAll($sql, $binds);
            if (! $ordered) {
                continue;
            }
            foreach ($ordered as $item) {
                $inserts[$item->dishId] = $item->dishId;
            }
            // 获取已有的关联
            $sql = "SELECT food_id FROM `foodcar_food_relation`"
                . " WHERE diner_id = ?";
            $exists = $this->db->fetchAll($sql, array($v->diner_id));
            foreach ($exists as $item) {
                // 需要更新的
                if (isset($inserts[$item->food_id])) {
                    $updates[$item->food_id] = $item->food_id;
                    unset($inserts[$item->food_id]);
                }
            }
            $this->db->beginTransaction();
            // 更新已有
            if ($updates) {
                $updates = implode(',', $updates);
                $sql = "UPDATE `foodcar_food_relation`"
                    . " SET sold_out = 0"
                    . " WHERE sold_out = 1"
                    . " AND diner_id = ?"
                    . " AND food_id IN({$updates})";
                $result = $this->db->execute($sql, array($v->diner_id));
                if ($result === false) {
                    $this->db->rollBack();
                    break;
                }
            }
            // 添加不存在的
            if ($inserts) {
                $values = array();
                foreach ($inserts as $item) {
                    $values[] = "({$item},{$v->diner_id},$v->merchant_id,1,0)";
                }
                $values = implode(',', $values);
                $sql = "INSERT INTO `foodcar_food_relation`"
                    . " (food_id,diner_id,merchant_id,status,sold_out)"
                    . " VALUES {$values}";
                $result = $this->db->execute($sql);
                if ($result === false) {
                    $this->db->rollBack();
                    break;
                }
            }
            $return[$v->diner_id] = $this->db->commit();
        }
        _log('autoUpdateDishRelation', json_encode($return));
        return $return;
    }

    /**
     * 追加
     * @param array $args
     * @return mixed
     */
    public function append($args = array()) {
        $data = $args['data'] ?: $_POST;
        if (! is_array($data['dish'])) {
            $data['dish'] = explode(',', rtrim(trim($data['dish']), ','));
        }
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        // 获取餐车信息
        $modelDiningcar = $this->model(':Diningcar');
        $result = $modelDiningcar->getItemById(
            $data['diner_id']
        );
        if (! $result) {
            return $this->export(array('code' => 409));
        }
        // 获取现有关联
        $exists = $this->model->getAll(array(
            'uid'      => $this->user->id,
            'diner_id' => $data['diner_id'],
            'fields'   => 'id,food_id'
        ));
        // 得到将更新项和追加项
        $update = array();
        foreach ($data['dish'] as $k => $v) {
            foreach ($exists as $k1 => $v1) {
                if ($v1->food_id == $v) {
                    $update[$v1->id] = true;
                    unset($data['dish'][$k]);
                    break;
                }
            }
        }
        // 不用更新
        if (empty($data['dish']) && empty($update)) {
            return $this->export(array('code' => 304));
        }
        if ($data['dish']) {
            $data = $this->prepareData($data);
            // 添加新关联
            $result = $this->model->add($data);
            if ($result === false) {
                return $this->export(array(
                    'code' => 500,
                    'message' => $this->db->sth->errorInfo()
                ));
            }
        }
        // 更新旧关联
        if ($update) {
            $this->model->update(
                array_keys($update),
                $this->user->id,
                array('sold_out' => 0)
            );
        }

        return $this->export();
    }

    /**
     * 更新
     */
    public function update($args = array()) {
        $data = $args['data'] ?: $_POST;
        if (! is_array($data['dish'])) {
            $data['dish'] = explode(',', rtrim(trim($data['dish']), ','));
        }
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        // 获取餐车、菜品关联
        $exists = $this->model->getAll(array(
            'diner_id' => $data['diner_id'],
            'fields'   => 'id,food_id'
        ));
        $dishIds = array();
        if ($exists) {
            foreach ($exists as $item) {
                $dishIds[] = $item->food_id;
            }   
        }
        if (empty($dishIds)) {
            return $this->export(array(
                'code'    => 600,
                'message' => '餐车无菜品，请先进行采购！'
            ));
        }

        $result = $this->model->update($data['dish'], $dishIds, $data['diner_id']);
        if ($result === false) {
            return $this->export(array(
                'code'    => 600,
                'message' => '更新失败，请稍后重试'
            ));
        }

        return $this->export(array(
            'code' => 200
        ));

        return $this->export(array('content' => $result));
    }
    
    /**
     * 删除
     */
    public function delete($args = array()) {
        $ids = $args['ids'];
        $result = $this->model->delete($ids, $this->user->id);
        
        return $this->export($result);
    }
    
    /**
     * 准备数据
     */
    private function prepareData($data) {
        $return = array();
        foreach ($data['dish'] as $v) {
            $return[] = array(
                'food_id'     => $v,
                'merchant_id' => $this->user->id,
                'diner_id'    => (int) $data['diner_id']
            );
        }

        return $return;
    }

    /**
     * 验证
     */
    private function validate($data) {
        $validator = $this->com('System:Validator\Validator');
        $validator->setOptions(array('breakOnError' => false));
        $rules = array(
            'diner_id' => array(
                'name' => '餐车id',
                'value' => $data['diner_id'],
                'rules' => array(
                    'required' => array(),
                    'number' => array()
                )
            ),
            'dish' => array(
                'name' => '关联菜品',
                'value' => $data['dish'],
                'rules' => array(
                    'required' => array(),
                )
            ),
        );
        $validator->validate($rules);

        return $validator->getErrors();
    }

    /**
     * 商户采购支付成功后更新餐车菜品表 foodcar_food_relation
     */
    public function relate($relations)
    {
        
        if(count($relations))
        {
            $add = '';
            foreach ($relations as $key => $value) 
            {
                if($value)
                {
                    $data = $this->model->find($value->dish_id,$value->diner_id);
                    
                    if($data)
                    {   //更新已有菜品的sold_out = 0
                        if($data->sold_out == 1)
                        {   
                            $modify = $this->model->modify($data->id);
                        }    
                    }else 
                    {
                        $add .= "({$value->dish_id},{$value->diner_id},{$value->merchant_id},1,0),";
                    }
                }
            }
            //批量添加采购订单菜品
            if($add)
            {
                $add = rtrim($add,',');
                $result = $this->model->addAll($add);
            }
        }
    }

}