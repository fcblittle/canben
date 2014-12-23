<?php 

namespace Module\Merchant\Controller\Api;

use Application\Controller\AppApi;

/**
 * 餐厅菜系API
 */
class Cuisine extends AppApi {

    public function init() {
        $this->model = $this->model(':Cuisine');
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
        $item = $this->model->getItem(array(
            'id' => $id,
        ));
        
        return $this->export($item);
    }
    
    /**
     * 获取列表
     */
    public function getList($args = array()) {
        $args['pager'] = array(
            'page'    => $args['page'],
            'limit'   => $args['limit'] ?: 20,
        );
        $args['merchant_id'] = $this->user->merchant_id;
        $list = $this->model->getList($args);

        return $this->export(array('content' => $list));
    }
    
    /**
     * 获取数据
     */
    public function getAll($args = array()) {
        $items = $this->model->getAll();

        return $this->export(array('content' => $items));
    }
}