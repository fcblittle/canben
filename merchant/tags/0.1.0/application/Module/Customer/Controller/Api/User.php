<?php 

namespace Module\Customer\Controller\Api;

use Application\Controller\AppApi;

/**
 * 用户API
 */
class User extends AppApi {

    private $model = null;

    public function init() {
        $this->model = $this->model(':User');
    }

    /**
     * 获取多个item
     * @param array $args
     */
    public function getItems($args = array()) {
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
        $result = $this->model->getItems(array(
            'id' => $id, 'fields' => $args['fields'] ?: '*'
        ));

        return $this->export(array('content' => $result));
    }

    /**
     * 获取item
     * @param array $args
     */
    public function getItem($args = array()) {
        $result = $this->model->getItem($args);

        return $this->export(array('content' => $result));
    }

}