<?php 

namespace Module\Merchant\Controller\Api;

use Application\Controller\AppApi;

/**
 * 菜品分类API
 */
class DishCategory extends AppApi {

    public function init() {
        $this->model = $this->model(':DishCategory');
    }

    /**
     * 获取
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
        
        return $this->export($item);
    }

    /**
     * 获取
     */
    public function getItems($args = array()) {
        $ids = $args['ids'];
        $items = array();
        if (! is_array($ids)) {
            $ids = rtrim(trim($ids), ',');
            $ids = explode(',', $ids);
        }
        $fields = $args['fields'] ?: '*';
        if (! $ids) {
            return $this->export(array(
                'code' => 400,
                'message' => 'Missing ID'
            ));
        }
        $items = $this->model->getItems(array(
            'ids'         => $ids,
            'merchant_id' => $this->user->id,
            'fields'      => $fields
        ));

        return $this->export(array('content' => $items));
    }

    /**
     * 获取数据
     */
    public function getAll($args = array()) {
        $items = $this->model->getAll(array(
            'merchant_id' => $this->user->id
        ));

        return $this->export(array('content' => $items));
    }
    
    /**
     * 添加
     */
    public function add($args = array()) {
        $data = $this->prepareData($args);
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        // 存在同名
        $item = $this->model->getItemByName(
            $this->user->id,
            $data['name']
        );
        if ($item) {
            return $this->export(array('code' => 409));
        }
        $result = $this->model->add($data);
        if ($result === false) {
            return $this->export(array('code' => 500));
        }
        return $this->export(array('content' => $result));
    }
    
    /**
     * 更新
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
        // 存在同名
        $item = $this->model->getItemByName(
            $this->user->id,
            $data['name']
        );
        if ($item && $item->id != $id) {
            return $this->export(array('code' => 409));
        }
        $result = $this->model->update($id, $this->user->id, $data);
        
        return $this->export($result);
    }
    
    /**
     * 删除
     */
    public function delete($args = array()) {
        $id = $args['id'];
        $result = $this->model->delete($id, $this->user->id);
        
        return $this->export($result);
    }
    
    /**
     * 获取表单数据
     */
    private function prepareData($args = array()) {
        $data = $args['data'] ?: $_POST;
        $data = array(
            'merchant_id'   => $this->user->id,
            'name'          => $data['name'],
            'weight'        => $data['weight'] ?: 0,
            'created'       => REQUEST_TIME
        );
        return $data;
    }
    
    /**
     * 验证
     */
    private function validate($data) {
        $validator = $this->com('System:Validator\Validator');
        $validator->setOptions(array('breakOnError' => false));
        $rules = array(
            'name' => array(
                'name' => '名称',
                'value' => $data['name'],
                'rules' => array(
                    'required' => array(),
                    'maxLength' => array('value' => 30, 'fullWidth' => true)
                )
            ),
        );
        $validator->validate($rules);
        
        return $validator->getErrors();
    }
}