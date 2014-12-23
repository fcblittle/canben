<?php 

namespace Module\Merchant\Controller\Api;

use Application\Controller\AppApi;

/**
 * 菜品相关API
 */
class Dish extends AppApi {

    private $model = null;

    public function init() {
        $this->model = $this->model(':Dish');
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
        $item = $this->model->getItemById($id, $this->user->merchant_id, $fields);
        $this->formatItem($item);
        
        return $this->export($item);
    }
    
    /**
     * 获取列表
     */
    public function getItemList($args = array()) {
        $args['pager'] = array(
            'page'    => $args['page'] ?: 0,
            'limit'   => $args['limit'] ?: 15
        );
        $args['uid'] = $this->user->merchant_id;
        $result = $this->model->getItemList($args);
        if ($result === false) {
            return $this->export(array('code' => 500));
        }
        if ($result['total']) {
            foreach ($result['list'] as & $v) {
                $this->formatItem($v);
            }
        }
        return $this->export(array('content' => $result));
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
        $result = $this->model->getItems($id, $this->user->merchant_id);
        if ($result) {
            foreach ($result as & $v) {
                $this->formatItem($v);
                $items[$v->id] = $v;
            }
        }

        return $this->export(array('content' => $items));
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
        $data = $this->prepareData($args);
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        $this->formatData($data);
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
        $data = $this->prepareData($args);
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        $this->formatData($data);
        $result = $this->model->update($id, $this->user->merchant_id, $data);
        if ($result === false) {
            return $this->export(array('code' => 500));
        }
        return $this->export(array('content' => $result));
    }
    
    /**
     * 删除
     */
    public function delete($args = array()) {
        $id = $args['id'];
        $result = $this->model->delete($id, $this->user->merchant_id);
        
        return $this->export($result);
    }
    
    /**
     * 获取表单数据
     */
    private function prepareData($args = array()) {
        $data = $args['data'] ?: $_POST;
        $data = array(
            'food_name'   => $data['food_name'],
            'price'       => $data['price'],
            'unit'        => $data['unit'],
            'description' => $data['description'],
            'images'      => $data['images'],
            'merchant_id' => $this->user->merchant_id,
            'cate_id'     => $data['cate_id'] ?: 0,
            //'tag_id'      => $data['tag_id'] ?: array(),
            'foodstatus'  => (int) $data['foodstauts'] ?: 0,
        );

        return $data;
    }

    private function formatData(& $data) {
        if (isset($data['tag_id'])) {
            $data['tag_id'] = implode(',', $data['tag_id']);
        }
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

    /**
     * 格式化item
     *
     * @param $item
     * @return mixed
     */
    private function formatItem(& $item) {
        if ($item->tag_id) {
            $item->tag_id = explode(',', $item->tag_id);
        }
        if ($item->images) {
            $item->images = explode(',', $item->images);
        }
    }
}