<?php

namespace Module\Fund\Controller;

use Application\Controller\Account;

class Profit extends Account
{
    public function __construct()
    {
        parent::__construct();

        if(! $this->checkPermission()) {
            $this->message->set('您无此模块权限！', 'error');
            $this->response->redirect("/fund/{$this->user->type}/index");
        }

        $this->view->active = 'fund/profit';
    }

    public function _default()
    {
        $pager = $this->com('System:Pager/Pager');

        $pagerParam = array(
            'page'  => $_GET['page'] ?: 0,
            'limit' => $_GET['limit'] ?: 10
        );
        
        $result = $this->call(
            'api/merchant/diningcar/getAll',
            array('fields' => 'id,diner_name')
        );
        if ($result['code'] != 200) {
            $this->error('数据库查询失败！', 'error');
        }
        $diners = $result['content'];

        $_GET['key'] = $_GET['key'] ?: -1;

        if ($_GET['key'] == -1) {
            $dinerIds = array_keys($diners);
        } else {
            $dinerIds = array((int)$_GET['key']);
        }

        $result = $this->call(
            'api/fund/profit/getItemList',
            array(
                'dinerIds'    => $dinerIds,
                'pager'       => $pagerParam,
                'start'       => $_GET['start'] ? strtotime($_GET["start"]) : 0,
                'end'         => $_GET['end'] ? (strtotime($_GET["end"]) + 86400 - 1) : mktime(23, 59, 59, date("n"),  date("j"), date("Y"))
            )   
        );

        foreach ($result['content']['list'] as & $v) {
            $v->allocation = json_decode($v->allocation);
        }

        $pagerParam['total'] = $result['content']['total'];
        $this->view->diners = $diners;
        $this->view->list = $result['content']['list'];
        // var_dump($this->view->list);die;
        $this->view->pager = $pager->render($pagerParam);
        $this->view->render(":profit.index");
    }
}