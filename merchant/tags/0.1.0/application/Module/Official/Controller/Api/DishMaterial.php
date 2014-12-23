<?php 

namespace Module\Official\Controller\Api;

use Application\Controller\AppApi;

/**
 * 官方菜品API
 */
class DishMaterial extends AppApi {

    private $model = null;

    public function init() {
        $this->model = $this->model(':DishMaterial');
    }

    /**
     * 获取items
     * @param array $args
     */
    public function getItems($args = array()) {
        $result = $this->model->getItems(array(
            'ids'    => $args['ids'],
            'fields' => $args['fields'] ?: '*'
        ));

        return $this->export(array('content' => $result));
    }

    /**
     * 获取版本items
     * @param array $args
     */
    public function getRevisionItems($args = array()) {
        $result = $this->model->getRevisionItems(array(
            'ids'    => $args['ids'],
            'fields' => $args['fields'] ?: '*'
        ));

        return $this->export(array('content' => $result));
    }
}