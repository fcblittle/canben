<?php 

namespace Module\Official\Controller\Api;

use Application\Controller\AppApi;

/**
 * 官方菜品API
 */
class Dish extends AppApi {

    private $model = null;

    public function init() {
        $this->model = $this->model(':Dish');
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

    /**
     * 获取items
     * @param array $args
     */
    public function getItems($args = array()) {
        $result = $this->model->getItems(array(
            'ids'    => $args['ids'],
            'fields' => $args['fields'] ?: '*'
        ));
        if ($result) {
            foreach ($result as & $item) {
                $this->formatItem($item);
            }
        }

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
        if ($result) {
            foreach ($result as & $item) {
                $this->formatItem($item);
            }
        }

        return $this->export(array('content' => $result));
    }

    private function formatItem(& $item) {
        if ($item->images) {
            $item->images = explode(',', $item->images);
        }
        if ($item->material) {
            $item->material = json_decode($item->material);
        }
    }
}