<?php 

namespace Module\Merchant\Controller\Api;

use Application\Controller\AppApi;

/**
 * 餐车相关API
 */
class Diningcar extends AppApi {
    
    public function init() {
        $this->model = $this->model(':Diningcar');
    }

    /**
     * 获取单个
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
        $item = $this->model->getItemById($id, $this->user->merchant_id, $fields);
        if ($item === false) {
            return $this->export(array('code' => 500));
        }

        return $this->export(array('content' => $item));
    }
    
    /**
     * 获取餐车列表
     */
    public function getItemList($args = array()) {
        $args['pager'] = array(
            'page'  => $args['page'] ?: 0,
            'limit' => $args['limit'] ?: 15
        );
        $args['uid'] = $this->user->merchant_id;
        $list = $this->model->getItemList($args);

        return $this->export(array('content' => $list));
    }

    /**
     * 获取多个item
     * @param array $args
     */
    public  function getItems($args = array()) {
        $id = $args['id'];
        $items = array();
        if (! is_array($id)) {
            $id = rtrim(trim($id), ',');
            $id = explode(',', $id);
        }
        if (! $id) {
            return $this->export(array(
                'code' => 400,
                'message' => 'Missing ID'
            ));
        }
        $result = $this->model->getItems(
            $id,
            $this->user->merchant_id,
            $args['fields'] ?: '*'
        );
        if ($result) {
            foreach ($result as & $v) {
                $items[$v->id] = $v;
            }
        }

        return $this->export(array('content' => $items));
    }

    /**
     * 获取全部
     */
    public function getAll($args = array()) {
        $items = $this->model->getAll(array(
            'uid' => $this->user->merchant_id,
            'fields' => $args['fields'] ?: '*',
            'status' => $args['status']
        ));
        $result = array();
        if ($items) {
            foreach ($items as & $v) {
                $result[$v->id] = $v;
            }
        }

        return $this->export(array('content' => $result));
    }

    /**
     * 总数
     */
    public function total($args = array()) {
        $result = $this->model->getTotal($this->user->merchant_id);

        return $this->export(array('content' => $result));
    }

    /**
     * 获取位置信息
     */
    public function getLocation($args = array()) {
        $items = $this->model->getAll(array(
            'fields' => 'id,merchant_id,diner_name,longitude,latitude,geohash',
            'uid' => $this->user->merchant_id
        ));
        foreach ($items as $item) {
            $this->formatItem($item);
        }

        return $this->export(array('content' => $items));
    }

    /**
     * 添加餐车
     */
    public function add($args = array()) {
        $data = $this->prepareData($args);
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        $result = $this->model->add($data);
        if ($result === false) {
            return $this->export(array('code' => 500));
        }
        return $this->export(array('content' => $result));
    }
    
    /**
     * 更新餐车
     */
    public function update($args = array()) {
        $id = (int) $args['id'];
        if (! $id) {
            return $this->export(array(
                'code' => 400,
                'message' => 'Missing ID'
            ));
        }
        $data = $this->prepareData($args);
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        $result = $this->model->update($id, $this->user->merchant_id, $data);
        if ($result === false) {
            return $this->export(array('code' => 500));
        }
        return $this->export(array('content' => $result));
    }
    
    /**
     * 删除餐车
     */
    public function delete($args = array()) {
        $id = (int) $args['id'];
        if (! $id) {
            return $this->export(array(
                'code' => 400,
                'message' => 'Missing ID'
            ));
        }
        $result = $this->model->delete($id, $this->user->merchant_id);
        if ($result === false) {
            return $this->export(array('code' => 500));
        }
        return $this->export(array('content' => $result));
    }
    
    /**
     * 获取表单数据
     */
    private function prepareData($args = array()) {
        $data = $args['data'] ?: $_POST;
        return array(
            'merchant_id'         => $this->user->merchant_id,
            'merchant_name'       => $this->user->merchant_name,
            'diner_name'          => $data['diner_name'],
            'first_person'        => $data['first_person'],
            'first_person_tel'    => $data['first_person_tel'],
            'second_person'       => $data['second_person'],
            'second_person_tel'   => $data['second_person_tel'],
            'trip_time1_start'    => $data['trip_time1_start'],
            'trip_time1_end'      => $data['trip_time1_end'],
            'trip_time2_start'    => $data['trip_time2_start'],
            'trip_time2_end'      => $data['trip_time2_end'],
            'trip_time3_start'    => $data['trip_time3_start'],
            'trip_time3_end'      => $data['trip_time3_end'],
            'description'         => $data['description'],
            'store_feature'       => $data['store_feature'],
            'delivery_yorn'       => $data['delivery_yorn'] ?: 1,
            'condition_and_range' => $data['condition_and_range'],
            'per_capita'          => $data['per_capita'],
            'store_stauts'        => (int) $data['store_stauts'] ?: 0,
            'into_time'           => REQUEST_TIME
        );
    }
    
    /**
     * 验证
     */
    private function validate($data) {
        $validator = $this->com('System:Validator\Validator');
        $validator->setOptions(array('breakOnError' => false));
        $rules = array(
            'first_person' => array(
                'name' => '第一联系人',
                'value' => $data['first_person'],
                'rules' => array(
                    'required' => array(),
                    'minLength' => array('value' => 2, 'fullWidth' => true),
                    'maxLength' => array('value' => 10, 'fullWidth' => true)
                )
            ),
            'second_person' => array(
                'name' => '第二联系人',
                'value' => $data['second_person'],
                'rules' => array(
                    'required' => array(),
                    'minLength' => array('value' => 2, 'fullWidth' => true),
                    'maxLength' => array('value' => 10, 'fullWidth' => true)
                )
            ),
            'second_person_tel' => array(
                'name' => '第二联系人电话',
                'value' => $data['second_person_tel'],
                'rules' => array(
                    'required' => array(),
                    'phone'    => array()
                )
            ),
            'trip_time1_start' => array(
                'name' => '早餐开始时间',
                'value' => $data['trip_time1_start'],
                'rules' => array(
                    'required' => array(),
                    'time'     => array('format' => 'G:i')
                )
            ),
            'trip_time1_end' => array(
                'name' => '早餐结束时间',
                'value' => $data['trip_time1_end'],
                'rules' => array(
                    'required' => array(),
                    'time'     => array('format' => 'G:i')
                )
            ),
            'trip_time2_start' => array(
                'name' => '午餐开始时间',
                'value' => $data['trip_time2_start'],
                'rules' => array(
                    'required' => array(),
                    'time'     => array('format' => 'G:i')
                )
            ),
            'trip_time2_end' => array(
                'name' => '午餐结束时间',
                'value' => $data['trip_time2_end'],
                'rules' => array(
                    'required' => array(),
                    'time'     => array('format' => 'G:i')
                )
            ),
            'trip_time3_start' => array(
                'name' => '晚餐开始时间',
                'value' => $data['trip_time3_start'],
                'rules' => array(
                    'required' => array(),
                    'time'     => array('format' => 'G:i')
                )
            ),
            'trip_time3_end' => array(
                'name' => '晚餐结束时间',
                'value' => $data['trip_time3_end'],
                'rules' => array(
                    'required' => array(),
                    'time'     => array('format' => 'G:i')
                )
            ),
            'description' => array(
                'name' => '餐车描述',
                'value' => $data['description'],
                'rules' => array(
                    'required' => array(),
                    'maxLength' => array('value' => 100, 'fullWidth' => true)
                )
            )
        );
        $validator->validate($rules);
        
        return $validator->getErrors();
    }

    /**
     * 格式化输出
     * @param $item
     */
    private function formatItem(& $item) {
        if ($item->longitude) {
            //$item->longitude += 0.00032;
        }
        if ($item->latitude) {
            //$item->latitude -= 0.0004;
        }
    }

}