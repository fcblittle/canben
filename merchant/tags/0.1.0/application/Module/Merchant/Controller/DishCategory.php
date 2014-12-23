<?php 

namespace Module\Merchant\Controller;

use Application\Controller\Account;

class DishCategory extends Account {

    /**
     * @path 首页
     */
    public function _default() {
        $data = $this->call(
            'api/merchant/dishCategory/getAll'
        );
        $this->view->items = $data['content'];
        $this->view->render(':dishCategory.index');
    }
    
    /**
     * 添加
     */
    public function add() {
        if ($_POST) {
            $result = $this->call(
                'api/merchant/dishCategory/add',
                array('list_id' => $this->listId)
            );
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
                $this->response->redirect(url('merchant/dishCategory'));
            }
        }
        $this->view->render(':dishCategory.edit');
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
                'api/merchant/dishCategory/update',
                array('id' => $id, 'list_id' => $this->listId)
            );
            if ($result['errors']) {
                foreach ($result['errors'] as $item) {
                    foreach ($item as $rule) {
                        $this->message->set($rule['message'], 'error');
                    }
                }
            } else {
                $this->message->set('修改成功', 'info');
            }
        }
        $item = $this->call(
            'api/merchant/dishCategory/getItem',
            array('id' => $id)
        );
        $this->view->item = $item['content'];
        $this->view->render(':dishCategory.edit');
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
            'api/merchant/dishCategory/delete',
            array('id' => $id)
        );
        if (! $result['errors']) {
            $this->message->set('删除成功', 'info');
        } else {
            $this->message->set('删除失败，请稍后重试', 'error');
        }
        $this->response->redirect('/merchant/dishCategory');
    }
}