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
        $item = $this->model->getItemById($id, $fields);
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
        $args['uid'] = $this->user->id;
        $list = $this->model->getItemList($args);

        // 获取餐车经营者
        $modelStaff = $this->model(':Staff');
        foreach ($list['list'] as $key => $item) {
            $list['list'][$key]->manager = $modelStaff->getDinerManager($item->id, 
                                            'username, realname');
        }

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
        $modelStaff = $this->model(':Staff');
        $result = $this->model->getItems(
            $id,
            $this->user->type === "manager"?$modelStaff->getItemById($this->user->dinerId, 0, "merchant_id")->merchant_id:$this->user->id,
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
     * 获取city_id
     */
    public function getCityId($args) {
        $result = array();

        if ($this->user->type === 'manager') {

            $item = $this->model->getCityId($args);
            
            $result['city_id'] = $item->city_id;
        }
        
        return $this->export(array('content' => $result));
    }

    /**
     * 根据餐车id获取
     */
    public function getItemById($args = array()) {
        $result = array();

        if ($this->user->type === 'manager') {

            $item = $this->model->getItemById($this->user->dinerId, '*');
            
            $result['area'] = $item->area;
        }
        
        return $this->export(array('content' => $result));
    }
    /**
     * 获取全部
     */
    public function getAll($args = array()) {
        $result = array();

        if ($this->user->type === 'merchant') {
            $items = $this->model->getAll(array(
                'uid'    => $this->user->id,
                'fields' => $args['fields'] ?: '*',
                'status' => $args['status'],
                'role'   => $args['role'] ?: null
            ));
            if ($items) {
                foreach ($items as & $v) {
                    $result[$v->id] = $v;
                }
            }
        } else if ($this->user->type === 'manager') {
            $modelStaff = $this->model(':Staff');

            $fields = $args['fields'] ?: '*';

            $item = $this->model->getItemById($this->user->dinerId, $fields);
            
            $result[$item->id] = $item;
        }
        
        
        return $this->export(array('content' => $result));
    }

    /**
     * 获取餐车经营者
     */
    public function getDinerManager($args = array())
    {
        $params = array_merge(array(
            'fields' => '*',
            'diner_id' => $args['diner_id'] ?: null
        ), $args);

        if ($this->user->type === 'manager') {
            return $this->export(array('content' => $this->user));
        }

        // 获取旗下餐车id
        $diner_id = $params['diner_id'];

        if ($diner_id === null) {
           $diner = $this->model->getAll(array(
                'fields' => 'id',
                'uid'    => $this->user->id
            ));

           foreach ($diner as $item) {
               $diner_id[] = $item->id;
           }
        }

        // 获取经营者
        $result = $this->model->getDinerManager($diner_id, $params['fields']);
        if ($result === false) {
            return $this->export(array(
                'code'    => 500,
                'message' => '数据库查询错误！'
            ));
        }

        $manager = array();
        foreach ($result as $item) {
            $manager[$item->diner_id] = $item;
        }

        return $this->export(array(
            'code'    => 200,
            'content' => $manager
        ));
    }

    /**
     * 总数
     */
    public function total($args = array()) {
        $result = $this->model->getTotal($this->user->id);

        return $this->export(array('content' => $result));
    }

    /**
     * 获取位置信息
     */
    public function getLocation($args = array()) {
        $items = $this->model->getAll(array(
            'fields' => 'id,merchant_id,diner_name,longitude,latitude,geohash',
            'uid' => $this->user->id
        ));
        foreach ($items as $item) {
            $this->formatItem($item);
        }

        return $this->export(array('content' => $items));
    }

    /**
     * 添加餐车
     */
    /*public function add($args = array()) {
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
    }*/
    
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

        // 获取餐车所属商户信息
        $diner = $this->model->getItemById($id, 'id, merchant_id, merchant_name');
        $params['data'] = $args['data'] ?: $_POST;
        $params['data']['merchant_id'] = $diner->merchant_id;
        $params['data']['merchant_name'] = $diner->merchant_name;

        $data = $this->prepareData($params);
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        $result = $this->model->update($id, $this->user->id, $data);
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
        $result = $this->model->delete($id, $this->user->id);
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
            'merchant_id'         => $data['merchant_id'],
            'merchant_name'       => $data['merchant_name'],
            'diner_name'          => $data['diner_name'],
            'trip_time1_start'    => $data['trip_time1_start'],
            'trip_time1_end'      => $data['trip_time1_end'],
            'trip_time2_start'    => $data['trip_time2_start'],
            'trip_time2_end'      => $data['trip_time2_end'],
            'trip_time3_start'    => $data['trip_time3_start'],
            'trip_time3_end'      => $data['trip_time3_end'],
            'description'         => $data['description'],
            'delivery_yorn'       => $data['delivery_yorn'],
            'condition_and_range' => $data['condition_and_range'],
            'per_capita'          => $data['per_capita'],
        );
    }
    
    /**
     * 验证
     */
    private function validate($data) {
        $validator = $this->com('System:Validator\Validator');
        $validator->setOptions(array('breakOnError' => false));
        $rules = array(
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
        
        if ($data['delivery_yorn']) {
            $rules['condition_and_range'] = array(
                                                'name'  => '送餐条件及范围',
                                                'value' => $data['condition_and_range'],
                                                'rules' => array(
                                                    'required' => array()
                                                )
                                            );
        }
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