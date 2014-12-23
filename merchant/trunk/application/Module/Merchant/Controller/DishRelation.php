<?php 

namespace Module\Merchant\Controller;

use Application\Controller\Account;

class DishRelation extends Account {

    public function __construct()
    {
        parent::__construct();

        $this->view->active = "merchant/dishRelation";
    }

    /**
     * @path 首页
     */
    public function _default() {
        $list = $dishIds = array();

        // 所有餐车
        $data = $this->call(
            'api/merchant/diningcar/getAll',
            array('fields' => 'id,merchant_id,diner_name', 'status' => 1)
        );
        $cars = $data['content'];
        $list = array_merge($list, $cars);
        $carIds = array();
        if ($cars) {
            foreach ($cars as $v) {
                $carIds[] = $v->id;
            }
        }
        // 餐车关联菜品
        $map['car'] = array();
        $data = $this->call(
            'api/merchant/dishRelation/getAll',
            array('diner_id' => $carIds, 'status' => 1)
        );
        if ($data['content']) {
            foreach ($data['content'] as $v) {
                if (! empty($dishIds[$v->food_id])) {
                    continue;
                }
                $map['car'][$v->diner_id][] = $v;
                $dishIds[$v->food_id] = true;
            }
        }
        $dishIds = array_keys($dishIds);
        // 菜品信息
        $data = $this->call(
            'api/official/dish/getItems',
            array('ids' => $dishIds)
        );
        
        $this->view->list = $list;
        $this->view->map = $map;
        $this->view->dishes = $data['content'];
        $this->view->render(':dishRelation.index');
    }
    
    /**
     * 添加
     */
    public function add() {
        if ($_POST) {
            $result = $this->call('api/merchant/dishRelation/add');
            if ($result['code'] === 400 && $result['errors']) {
                foreach ($result['errors'] as $item) {
                    foreach ($item as $rule) {
                        $this->message->set($rule['message'], 'error');
                    }
                }
            }
            if ($result['code'] === 500) {
                $this->message->set('添加失败，请稍后重试', 'error');
            }
            if ($result['code'] === 200) {
                $this->message->set('添加成功', 'info');
                $this->response->redirect(url('merchant/dish'));
            }
        }

        $this->view->render(':dishRelation.add');
    }
    
    /**
     * 编辑
     */
    public function edit() {
        $dinerId = (int) $_GET['diner_id'];
        if (!$dinerId) {
            $this->error('缺少参数', 400);
        }
        // todo: 权限验证
        if (! $this->checkDiner($dinerId)) {
            $this->message->set('您无此权限！', 'error');
            redirect(url('merchant/dishRelation'));
        }

        if ($_POST) {
            $result = $this->call(
                'api/merchant/dishRelation/update',
                array('diner_id' => $dinerId)
            );
            if ($result['code'] === 400 && $result['errors']) {
                foreach ($result['errors'] as $item) {
                    foreach ($item as $rule) {
                        $this->message->set($rule['message'], 'error');
                    }
                }
            }
            if ($result['code'] === 600) {
                $this->message->set($result['message'], 'error');
                redirect(url('merchant/dishRelation'));
            }
            if ($result['code'] === 500) {
                $this->message->set('更新失败，请稍后重试', 'error');
            }
            if ($result['code'] === 304) {
                $this->message->set('数据无需更新', 'warning');
            }
            if ($result['code'] === 200) {
                $this->message->set('编辑成功', 'success');
                $this->response->redirect(url('merchant/dishRelation'));
            }
        }
        $result = $this->call(
            'api/merchant/dishRelation/getAll',
            array('diner_id' => $_GET['diner_id'])
        );
        $dishIds = $result['content'];

        $dishes = $this->call(
            'api/merchant/dishRelation/getCityDinerDish',
            array(
                'fields'   => 'dish.id, dish.food_name',
                'diner_id' => $_GET['diner_id']
            )
        );
        $cityDishes = array();
        foreach ($dishes['content'] as $item) {
            $cityDishes[$item->id] = $item->food_name;
        }

        $this->view->dish = $dishIds;
        $this->view->dishes = $cityDishes;
        $this->view->render(':dishRelation.edit');
    }
    
    /**
     * 删除
     */
    public function delete() {
        $id = $_GET['id'];
        if (! $id) {
            $this->error('缺少参数', 400);

        }
        $result = $this->call(
            'api/merchant/dishRelation/delete',
            array('id' => $id)
        );
        if (! $result['errors']) {
            $this->message->set('删除成功', 'info');
        } else {
            $this->message->set('删除失败，请稍后重试', 'error');
        }
        $this->response->redirect('/merchant/dishRelation');
    }

    private function checkDiner($dinerId)
    {
        // 获取餐车信息
        $result = $this->call(
            'api/merchant/diningcar/getAll',
            array('status' => 1)
        );
        
        if (array_key_exists($dinerId, $result['content'])) {
            return true;
        }

        return false;
    }
}