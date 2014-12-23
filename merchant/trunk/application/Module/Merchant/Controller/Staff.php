<?php 

namespace Module\Merchant\Controller;

use Application\Controller\Account;

class Staff extends Account {
    
    public function __construct()
    {
        parent::__construct();

        $this->view->active = "merchant/staff";
    }

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

        $result = $this->call('api/merchant/diningcar/getAll', array('role' => 1));

        if (!($result['content'])) {
            //托管户无法进行增加操作
            $this->message->set('您无自营餐车，无法新增店员', 'info');
            $this->response->redirect(url('merchant/staff'));
        }
        if ($_POST) {
            // 验证POST提交的dinerId是否有效
            $dinerId = ! empty($_POST["diner_id"]) ? $_POST["diner_id"] : $this->user->dinerId;
            if (!array_key_exists($dinerId, $result['content'])) {
                $this->response->redirect(url('merchant/staff'));
            }

            $result = $this->call('api/merchant/staff/add');
            if ($result['code'] === 400 && $result['errors']) {
                foreach ($result['errors'] as $item) {
                    foreach ($item as $rule) {
                        $this->message->set($rule['message'], 'error');
                    }
                }
            }
            if ($result['code'] === 250) {
                $this->message->set('两次输入密码不一致', 'error');
            }
            if ($result['code'] === 500) {
                $this->message->set('添加失败，请稍后重试', 'error');
            }
            if ($result['code'] === 200) {
                $this->message->set('添加成功', 'info');
                $this->response->redirect(url('merchant/staff'));
            }
        }
        

        $result = $this->call('api/merchant/diningcar/getAll', array('role' => 1));
        
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
            $result = $this->call('api/merchant/diningcar/getAll', array('role' => 1));
            // 验证POST提交的dinerId是否有效
            $dinerId = (int)$_POST["diner_id"] ?: $this->user->dinerId;
            if (!array_key_exists($dinerId, $result['content'])) {
                $this->message->set('您无此权限！', 'error');
                $this->response->redirect(url('merchant/staff'));
            }
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
            if ($result['code'] === 250) {
                $this->message->set('两次输入密码不一致', 'error');
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
            'api/merchant/staff/getItemById',
            array('id' => $id)
        );
        $result = $this->call('api/merchant/diningcar/getAll', array('role' => 1));
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
            $this->message->set('禁用成功', 'info');
        } else {
            $this->message->set('禁用失败，请稍后重试', 'error');
        }
        $this->response->redirect('/merchant/staff');
    }
}