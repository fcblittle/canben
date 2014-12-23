<?php 

namespace Module\Official\Controller\Api;

use Application\Controller\AppApi;

/**
 * 经营区域API
 */
class Area extends AppApi {

    private $model = null;

    public function init() {
        $this->model = $this->model(':Area');
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