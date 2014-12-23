<?php 

namespace Module\Merchant\Controller\Api;

use Application\Controller\AppApi;

/**
 * 官方菜品标签API
 */
class OfficialDishTag extends AppApi {

    private $model = null;

    public function init() {
        $this->model = $this->model(':OfficialDishTag');
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
        $items = $this->model->getAll();

        return $this->export(array('content' => $items));
    }

    /**
     * 格式化item
     *
     * @param $item
     * @return mixed
     */
    private function formatItem(& $item) {
        if ($item->cate_id) {
            $item->cate_id = explode(',', $item->cate_id);
        }
        if ($item->images) {
            $item->images = explode(',', $item->images);
        }
    }
}