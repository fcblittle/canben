<?php 

namespace Module\Merchant\Controller\Api;

use Application\Controller\AppApi;

/**
 * 官方菜品相关API
 */
class OfficialDish extends AppApi {

    private $model = null;

    public function init() {
        $this->model = $this->model(':OfficialDish');
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
        $item = $this->model->getItemById($id, $this->user->id, $fields);
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
        $args['uid'] = $this->user->id;
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
     * 获取某城市的菜品
     * @param array $args
     */
    public  function getCityItems($args = array()) {
        $id = $args['id'];
        $items = array();
        if (is_array($id)) {
            $id = implode(',', $id);
            $id = rtrim(trim($id), ',');
        }
        if (! $id) {
            return $this->export(array(
                'code' => 400,
                'message' => 'Missing ID'
            ));
        }
        $result = $this->model->getCityItems($id, $args['fields'] ?: '*');
        // var_dump($result);die;
        if ($result) {
            foreach ($result as & $v) {
                $this->formatItem($v);
                $items[$v->id] = $v;
            }
        }

        return $this->export(array('content' => $items));
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
        $result = $this->model->getItems($id, $args['fields'] ?: '*');
        if ($result) {
            foreach ($result as & $v) {
                $this->formatItem($v);
                $items[$v->id] = $v;
            }
        }

        return $this->export(array('content' => $items));
    }

    public function getDishRevision($args)
    {
        $params = array_merge(array(
            'id' => null
        ), $args);

        $dishes = $this->model->getDishRevision($args);
        if ($dishes === false) {
            return $this->export(array(
                'code' => 500
            ));
        }

        return $this->export(array(
            'code'    => 200,
            'content' => $dishes
        ));
    }

    /**
     * 获取全部
     */
    public function getAll($args = array()) {
        $items = $this->model->getAll($args);
        $result = array();
        if ($items) {
            foreach ($items as & $v) {
                $this->formatItem($v);
                $result[$v->id] = $v;
            }
        }
        return $this->export(array('content' => $result));
    }

    /**
     * 格式化item
     *
     * @param $item
     * @return mixed
     */
    private function formatItem(& $item) {
        if ($item->images) {
            $item->images = explode(',', $item->images);
        }
        if ($item->material) {
            $item->material = json_decode($item->material);
        }
    }

    /**
     * 获取城市菜品
     */
    public function getCityDish($args = array()) {
        $items = array();

        if ($this->user->type === 'manager') {
            
            $items = $this->model->getCityDish($args);
            
        }
        // var_dump($items);die;
        
        $result = array();
        if ($items) {
            foreach ($items as & $v) {
                
                $result[] = $v->food_id;
            }
        }
        return $this->export(array('content' => $result));
    }
}