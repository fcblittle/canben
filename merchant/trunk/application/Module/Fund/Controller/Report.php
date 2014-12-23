<?php

namespace Module\Fund\Controller;

use Application\Controller\Account;

class Report extends Account
{
    public function __construct()
    {
        parent::__construct();

        $this->view->active = 'fund/report';
        
    }

    public function _default()
    {
        if ($_POST) {
            $time = $_POST['time'];
            $result = $this->call("api/fund/report/transfer", array("time" => $time));
            if ($result['code'] == 200) {
                $this->message->set($result['message'], 'info');
            } else {
                $this->message->set($result['message'], 'error');
            }
            
        }
        $result = $this->call(
            'api/merchant/diningcar/getAll',
            array('fields' => 'id,diner_name')
        );
        if ($result['code'] != 200) {
            $this->error('数据库查询失败！', 'error');
        }
        $diner = $result["content"];
        foreach ($diner as $v) {
            $dinerIds[] = $v->id;
        }

        // 取得get传参的值
        // echo date("Y-m-d H:i:s", strtotime("2014-09-24"));die;
        $today = mktime(0, 0, 0, date("n"), date("j") + 1, date("Y")) - 1;
        $monthAgo = mktime(0, 0, 0, date("n"), date("j"), date("Y")) - 2678400;
        $start = $_GET["start"] === null ? $monthAgo : strtotime($_GET["start"]);
        $end = $_GET["end"] === null ? $today : strtotime($_GET["end"]) + 86400 - 1;
        $keyword = $_GET["key"] === null ? "" : $_GET["key"];
        $keyword = $keyword == -1 ? "" : $keyword;
        $result = $this->call("api/fund/report/getItemList", array(
            'diner_id' => $dinerIds, 
            'start' => $start,
            'end' => $end,
            'keyword' => $keyword,
        ));
        if ($result['code'] != 200) {
            $this->error('数据库查询失败！', 'error');
        }
        $list = $result["content"]["list"];
        
        $result = $this->call("api/fund/report/translate", array('list' => $list));

        
        $this->view->result = $result;
        $this->view->diner = $diner;
        $this->view->render(":report.index");
    }

    public function add()
    {
        if ($this->user->type !== "manager") {
            $this->response->redirect("/fund/report");
        }
        // 每日统计时间
        if (! $this->checkStatisticTime()) {
            $this->message->set('已超过每日线下提报时间，无法提报</br>(每日提报时间&nbsp;&nbsp;00:00~22:00)', 'info');
            $this->response->redirect("/fund/report");
        }
        if ($this->call("api/fund/report/isExisting")) {
            $this->message->set('今日已有提报，无法重复添加', 'error');
            $this->response->redirect("/fund/report");
        }

        // 城市菜品
        $result = $this->call(
            'api/merchant/dishRelation/getCityDinerDish',
            array(
                'fields' => 'dish.id, dish.food_name, dish.sale_price',
                'diner_id' => $this->user->dinerId
            )
        );
        $dishes = array();
        foreach ($result['content'] as $item) {
            $dishes[$item->id] = $item;
        }

        $result = $this->call(
            'api/merchant/dishRelation/getAll',
            array('diner_id' => $this->user->dinerId, 'status' => 1)
        );
        $items = array();
        foreach ($result['content'] as $item) {
            if (array_key_exists($item->food_id, $dishes)) {
                $item->food_name = $dishes[$item->food_id]->food_name;
                $item->sale_price = $dishes[$item->food_id]->sale_price;

                $items[] = $item;
            }
        }

        if ($_POST){
            $result = $this->call("api/fund/report/add");
            if ($result['code'] === 500) {
                $this->message->set('添加失败，请稍后重试', 'error');
            }
            if ($result['code'] === 200) {
                $this->message->set($result['message'], 'info');
                $this->response->redirect("/fund/report");
            }
        }

        $this->view->online = $this->call("api/fund/report/getOnlineByTimeAndDinerId", array(
                "time" => time(),
                "dinerId" => $this->user->dinerId
            ));
        $this->view->foods = $items;
        $this->view->render(":report.add");
    }
    public function view()
    {
        $args = $this->request->arg();
        $flag = true;
        $dinerId = $args[3] or $flag = false;
        $created = $args[4] or $flag = false;
        if (!$flag) {
            $this->error('未能获得有效参数！', 'error');
        }
        
        $start = mktime(0, 0, 0, date('m', $created), date('d', $created), date('Y', $created));
        $end = mktime(0, 0, 0, date('m', $created), date('d', $created) + 1, date('Y', $created)) - 1;
       

        $result = $this->call("api/fund/report/getItemList", array(
            'diner_id' => $dinerId, 
            'start' => $start,
            'end' => $end,
        ));
        if ($result['code'] != 200) {
            $this->error('数据库查询失败！', 'error');
        }
        $list = $result["content"]["list"];
        
        $result = $this->call("api/fund/report/translate", array('list' => $list));
        
        foreach ($result as $key => $value) {
            $result = $value;
        }

        $this->view->online = $result["online"];
        $this->view->offline = $result["offline"];
        unset($result["online"]);
        unset($result["offline"]);
        $this->view->result = $result;
        $this->view->render(":report.view");
    }

    public function delete()
    {
        $time = $this->request->arg(3);
        if (empty($time)) {
            $this->message->set('无效的参数！', 'error');
            redirect('/fund/report');
        }

        // 权限
        if(! $this->checkManagerPermission()) {
            $this->message->set('您无此权限！', 'error');
            redirect('/fund/report');
        }
        $date = $this->getDateInterval($time);

        $params = array_merge(array(
            'fields'   => 'a.*',
            'diner_id' => $this->user->dinerId, 
        ), $date);

        $result = $this->call("api/fund/report/getItemList", $params);
        if (empty($result['content']['list'])) {
            $this->message->set('该日期未提报！', 'error');
            redirect('/fund/report');
        }
        // 是否已支付
        $ids = array();
        foreach ($result['content']['list'] as $item) {
            if (! empty($item->transfered)) {
                $this->message->set('该提报已支付，不可删除！', 'error');
                redirect('/fund/report');
            }

            $ids[] = $item->id;
        }
        // 删除
        $result = $this->call(
            'api/fund/report/delete', 
            array('ids' => $ids)
        );
        if ($result === false) {
            $this->message->set(date('Y年m月d日', $time) . ' 线下提报 删除失败, 请稍后重试', 'error');
        }

        $this->message->set(date('Y年m月d日', $time) . ' 线下提报 删除成功', 'success');
        redirect('/fund/report');
    }

    protected function getDateInterval($time)
    {
        $year  = date('Y', $time);
        $month = date('m', $time);
        $day   = date('d', $time);

        return array(
            'start' => mktime(0, 0, 0, $month, $day, $year),
            'end'   => mktime(0, 0, 0, $month, $day + 1, $year)
        );
    }

    private function checkStatisticTime()
    {
        return ! (date('H', time()) >= 22);
    }
}