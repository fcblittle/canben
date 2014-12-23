<?php 

namespace Module\Merchant\Controller;

use Application\Controller\Account;

class Staff extends Account {

    /**
     * @path 首页
     */
    public function _default() {
        $pager = $this->com('System:Pager\Pager');
        $page = $_GET['page'] ?: 0;
        $limit = $_GET['limit'] ?: 15;
        $data = $this->call(
            'api/merchant/staff/getItemList',
            array('page' => $page, 'limit' => $limit)
        );
        $pagerParams = array(
            'page'    => $page,
            'limit'   => $limit,
            'total'   => $data['content']['total']
        );
        // 获取餐车
        if ($data['content']['list']) {
            $carIds = array();
            foreach ($data['content']['list'] as $v) {
                $v->diner_id && $carIds[$v->diner_id] = $v->diner_id;
            }
            if ($carIds) {
                $result = $this->call(
                    'api/merchant/diningcar/getItems',
                    array('id' => $carIds)
                );
                $this->view->cars = $result['content'];
            }
        }

        $this->view->list = $data['content']['list'];
        $this->view->pager = $pager->render($pagerParams);
        $this->view->render(':staff.index');
    }
    
    /**
     * 添加
     */
    public function add() {
        if ($_POST) {
            $result = $this->call('api/merchant/staff/add');
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
                $this->response->redirect(url('merchant/staff'));
            }
        }
        $result = $this->call('api/merchant/diningcar/getAll');
        $this->view->cars = $result['content'];
        $this->view->render(':staff.edit');
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
                'api/merchant/staff/update',
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
                $this->message->set('编辑成功', 'info');
                $this->response->redirect(url('merchant/staff'));
            }
        }
        $item = $this->call(
            'api/merchant/staff/getItem',
            array('id' => $id)
        );
        $result = $this->call('api/merchant/diningcar/getAll');
        $this->view->cars = $result['content'];
        $this->view->item = $item['content'];
        $this->view->render(':staff.edit');
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
            'api/merchant/staff/delete',
            array('id' => $id)
        );
        if (! $result['errors']) {
            $this->message->set('删除成功', 'info');
        } else {
            $this->message->set('删除失败，请稍后重试', 'error');
        }
        $this->response->redirect('/merchant/staff');
    }
}