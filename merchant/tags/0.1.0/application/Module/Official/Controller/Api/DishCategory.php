<?php 

namespace Module\Official\Controller\Api;

use Application\Controller\AppApi;

/**
 * 官方菜品API
 */
class DishCategory extends AppApi {

    private $model = null;

    public function init() {
        $this->model = $this->model(':DishCategory');
    }

    /**
     * 获取全部
     * @param array $args
     */
    public function getAll($args = array()) {
        $result = $this->model->getAll(array(
            'fields' => $args['fields'] ?: '*'
        ));

        return $this->export(array('content' => $result));
    }
}