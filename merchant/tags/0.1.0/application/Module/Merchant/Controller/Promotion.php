<?php 

namespace Module\Merchant\Controller;

use Application\Controller\Account;

class Promotion extends Account {

    /**
     * @path 首页
     */
    public function _default() {
        $pager = $this->com('System:Pager\Pager');
        $page = $_GET['page'] ?: 0;
        $limit = $_GET['limit'] ?: 15;
        $data = $this->call(
            'api/merchant/promotion/getItemList',
            array('page' => $page, 'limit' => $limit)
        );
        if ($data['content']['list']) {
            $dishIds = array();
            foreach ($data['content']['list'] as $v) {
                $v->dish && $dishIds = array_merge($dishIds, $v->dish);
            }
            $dishIds = array_unique($dishIds);
            $dataDishes = $this->call(
                'api/merchant/dish/getItems',
                array('id' => $dishIds)
            );
            $this->view->dishes = $dataDishes['content'];
        }
        $pagerParams = array(
            'page'    => $page,
            'limit'   => $limit,
            'total'   => $data['content']['total']
        );
        $typesData = $this->call(
            'api/merchant/datalist/getData',
            array('listId' => 1)
        );
        if ($typesData['content']) {
            foreach ($typesData['content'] as $v) {
                $types[$v->id] = $v;
            }
        }
        $this->view->list = $data['content']['list'];
        $this->view->types = $types;
        $this->view->pager = $pager->render($pagerParams);
        $this->view->render(':promotion.index');
    }
    
    /**
     * 添加
     */
    public function add() {
        if ($_POST) {
            $result = $this->call('api/merchant/promotion/add');
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
                $this->message->set('添加促销成功', 'info');
                $this->response->redirect(url('merchant/promotion'));
            }
        }
        $types = $this->call(
            'api/merchant/datalist/getData',
            array('listId' => 1)
        );
        $dishes = $this->call(
            'api/merchant/dish/getAll',
            array('fields' => 'id,food_name,price,unit')
        );

        $this->view->types = $types['content'];
        $this->view->dishes = $dishes['content'];
        $this->view->render(':promotion.edit');
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
                'api/merchant/promotion/update', 
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
                $this->message->set('促销保存成功', 'info');
                //$this->response->redirect(url('merchant/promotion'));
            }
        }
        $item = $this->call(
            'api/merchant/promotion/getItem', 
            array('id' => $id)
        );
        $types = $this->call(
            'api/merchant/datalist/getData',
            array('listId' => 1)
        );
        $dishes = $this->call(
            'api/merchant/dish/getAll',
            array('fields' => 'id,food_name,price,unit')
        );

        $this->view->item = $item['content'];
        $this->view->types = $types['content'];
        $this->view->dishes = $dishes['content'];
        $this->view->render(':promotion.edit');
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
            'api/merchant/promotion/delete',
            array('id' => $id)
        );
        if ($result['code'] === 200) {
            $this->message->set('促销删除成功', 'info');
        } else {
            $this->message->set('促销删除失败，请稍后重试', 'error');
        }
        $this->response->redirect('/merchant/promotion');
    }
}