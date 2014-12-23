<?php 

namespace Module\Merchant\Controller\Api;

use Application\Controller\AppApi;

/**
 * 菜品相关API
 */
class DishTag extends AppApi {

    public function init() {
        $this->model = $this->model(':DishTag');
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
        $item = $this->model->getItemById($id, $fields);
        
        return $this->export($item);
    }
    
    /**
     * 获取列表
     */
    public function getItemList($args = array()) {
        $args['pager'] = array(
            'page'    => $args['page'],
            'limit'   => $args['limit'] ?: 15
        );
        $args['uid'] = $this->user->merchant_id;
        $args['keyword'] = $args['keyword'];
        $list = $this->model->getItemList($args);

        return $this->export(array('content' => $list));
    }
    
    /**
     * 获取全部
     */
    public function getAll($args = array()) {
        $items = $this->model->getAll(array('uid' => $this->user->merchant_id));
        
        return $this->export(array('content' => $items));
    }
    
    /**
     * 添加
     */
    public function add($args = array()) {
        $data = $this->getFormData($args);
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        $id = $this->model->add($data);
        
        return $this->export($id);
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
        $data = $this->getFormData($args);
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        $result = $this->model->update($id, $data);
        
        return $this->export($result);
    }
    
    /**
     * 删除
     */
    public function delete($args = array()) {
        $id = $args['id'];
        $result = $this->model->delete($id);
        
        return $this->export($result);
    }
    
    /**
     * 获取表单数据
     */
    private function getFormData($args = array()) {
        $formData = $args['data'] ?: $_POST;
        $data = array(
            'food_name'   => $formData['food_name'],
            'price'       => $formData['price'],
            'unit'        => $formData['unit'],
            'description' => $formData['description'],
            'store_id'    => (int) $formData['store_id'],
            'cate_id'     => $formData['cate_id'],
            'foodstauts'  => (int) $formData['foodstauts'],
        );
        if ($formData['images']) {
            $data['images'] = $formData['images'];
        }
        return $data;
    }
    
    /**
     * 验证
     */
    private function validate($data) {
        $validator = $this->com('System:Validator\Validator');
        $validator->setOptions(array('breakOnError' => false));
        $rules = array(
            'food_name' => array(
                'name' => '菜品名称',
                'value' => $data['food_name'],
                'rules' => array(
                    'required' => array(),
                    'maxLength' => array('value' => 10, 'fullWidth' => true)
                )
            ),
            'price' => array(
                'name' => '菜品单价',
                'value' => $data['price'],
                'rules' => array(
                    'required' => array(),
                    'number' => array()
                )
            ),
        );
        $validator->validate($rules);
        
        return $validator->getErrors();
    }
}