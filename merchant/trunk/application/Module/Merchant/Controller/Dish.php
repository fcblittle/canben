<?php 

namespace Module\Merchant\Controller;

use Application\Controller\Account;

class Dish extends Account {

    /**
     * @path 首页
     */
    public function restaurant() {
        $pager = $this->com('System:Pager\Pager');
        $page = $_GET['page'] ?: 0;
        $limit = $_GET['limit'] ?: 15;
        $dishes = $categories = array();
        if ($_GET['type'] === 'foodname') {
            $foodname = $_GET['key'];
        }
        $result = $this->call(
            'api/merchant/dish/getItemList',
            array(
                'page'     => $page,
                'limit'    => $limit,
                'status'   => isset($_GET['status']) ? $_GET['status'] : -1,
                'category' => $_GET['category'] ?: -1,
                'keyword'  => $foodname ?: ''
            )
        );
        $dishes = $result['content'];
        $pagerParams = array(
            'page'    => $page,
            'limit'   => $limit,
            'total'   => $dishes['total']
        );
        $result = $this->call(
            'api/merchant/dishCategory/getAll'
        );
        $categories = $result['content'];

        $this->view->list = $dishes['list'];
        $this->view->categories = $categories;
        $this->view->pager = $pager->render($pagerParams);
        $this->view->render(':dish.restaurant');
    }

    /**
     * 餐车菜品
     */
    public function diner() {
        $diners = $this->call('api/merchant/diningcar/getAll');
    }

    /**
     * 添加
     */
    public function add() {
        if ($_POST) {
            $result = $this->call('api/merchant/dish/add');
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
                $this->response->redirect(url('merchant/dish/restaurant'));
            }
        }
        $categories = $this->call(
            'api/merchant/dishCategory/getAll'
        );
        $units = array('份', '盘', '盒');

        $this->view->categories = $categories['content'];
        $this->view->units = $units;
        $this->view->render(':dish.add');
    }
    
    /**
     * 编辑
     */
    public function edit() {
        $id = (int) $_GET['id'];
        if (! $id) {
            $this->error('缺少参数', 400);
        }
        if ($_POST) {
            $result = $this->call(
                'api/merchant/dish/update', 
                array('id' => $id)
            );
            if ($result['code'] === 400 && $result['errors']) {
                foreach ($result['errors'] as $item) {
                    foreach ($item as $rule) {
                        $this->message->set($rule['message'], 'error');
                    }
                }
            }
            if ($result['code'] === 500) {
                $this->message->set('更新失败，请稍后重试', 'error');
            }
            if ($result['code'] === 200) {
                $this->message->set('菜品编辑成功', 'info');
                $this->response->redirect(url('merchant/dish/restaurant'));
            }
        }
        $item = $this->call(
            'api/merchant/dish/getItem', 
            array('id' => $id)
        );
        $categories = $this->call(
            'api/merchant/dishCategory/getAll'
        );
        $units = array('份', '盘', '盒');
        
        $this->view->item = $item['content'];
        $this->view->categories = $categories['content'];
        $this->view->units = $units;
        $this->view->render(':dish.add');
    }
    
    /**
     * 删除
     */
    public function delete() {
        $id = (int) $_GET['id'];
        if (! $id) {
            $this->error('缺少参数', 400);

        }
        $result = $this->call(
            'api/merchant/dish/delete',
            array('id' => $id)
        );
        if (! $result['errors']) {
            $this->message->set('菜品删除成功', 'info');
        } else {
            $this->message->set('菜品删除失败，请稍后重试', 'error');
        }
        $this->response->redirect('/merchant/dish');
    }
}