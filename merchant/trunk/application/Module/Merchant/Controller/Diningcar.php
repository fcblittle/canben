<?php 

namespace Module\Merchant\Controller;

use Application\Controller\Account;

class Diningcar extends Account {

    public function __construct()
    {
        parent::__construct();

        $this->view->active = "merchant/diningcar";
    }

    /**
     * @path 首页
     */
    public function _default() {
        if (! $this->checkMerchantPermission()) {
            $this->response->redirect("/welcome");
        }

        $pager = $this->com('System:Pager\Pager');
        $page = $_GET['page'] ?: 0;
        $limit = $_GET['limit'] ?: 15;
        $data = $this->call(
            'api/merchant/diningcar/getItemList',
            array('page' => $page, 'limit' => $limit)
        );
        $pagerParams = array(
            'page'    => $page,
            'limit'   => $limit,
            'total'   => $data['content']['total']
        );

        
        $this->view->list = $data['content']['list'];
        $this->view->pager = $pager->render($pagerParams);
        $this->view->render(':diningcar.index');
    }
    
    /**
     * 添加
     */
    /*public function add() {
        if ($_POST) {
            $result = $this->call('api/merchant/diningcar/add');
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
                $this->message->set('添加餐车成功', 'info');
                $this->response->redirect(url('merchant/diningcar'));
            }
        }
        $this->view->render(':diningcar.edit');
    }*/
    
    /**
     * 编辑
     */
    public function edit() {
        if (! $this->checkMerchantPermission()) {
            $this->error('您无此权限！');
        }

        $id = (int) $_GET['id'];
        if (! $id) {
            $this->error('缺少参数', 400);
        }
        if ($_POST) {
            $result = $this->call(
                'api/merchant/diningcar/update',
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
                $this->message->set('编辑失败，请稍后重试', 'error');
            }
            if ($result['code'] === 200) {
                $this->message->set('编辑餐车成功', 'info');
                $this->response->redirect(url('merchant/diningcar'));
            }
        }
        $item = $this->call(
            'api/merchant/diningcar/getItem', 
            array('id' => $id)
        );
       
        $result = $this->call('api/official/area/getAll');
        $areas = $result['content'];

        $this->view->item = $item['content'];
        $this->view->areas = $areas;
        $this->view->render(':diningcar.edit');
    }

    /**
     * 删除
     */
    public function delete() {
        if (! $this->checkMerchantPermission()) {
            $this->error('您无此权限！');
        }
        
        $id = (int) $_GET['id'];
        if (! $id) {
            $this->error('缺少参数', 400);
        }
        $result = $this->call(
            'api/merchant/diningcar/delete',
            array('id' => $id)
        );
        if ($result['code'] === 200 && $result['content'] === 1) {
            $this->message->set('餐车删除成功', 'info');
        } else {
            $this->message->set('餐车删除失败，请稍后重试', 'error');
        }
        $this->response->redirect('/merchant/diningcar');
    }

    /**
     * 餐车定位
     */
    public function location() {
        $this->view->active = "merchant/diningcar/location";
        $this->view->render(':diningcar.location');
    }
}