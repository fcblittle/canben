<?php

namespace Module\Fund\Controller\Api;

use Application\Controller\AppApi;

class Report extends AppApi
{
    public function init()
    {
        $this->model = $this->model(':Report');
    }


    /**
     * 转换提报列表数据
     */
    public function translate($args = array())
    {
        $result = array();
        $list = $args["list"];

        if (is_array($list)) {
            foreach ($list as $k => $v) {
                $date = date("Y-m-d", $v->created);
                $result[$date." ".$v->diner_id][] = array(
                        "diner_id" => $v->diner_id,
                        "diner_name" => $v->diner_name,
                        "dish_reversion_id" => $v->dish_reversion_id,
                        "food_name" => $v->food_name,
                        "sale_price" => $v->sale_price,
                        "count" => $v->count,
                        "created" => $v->created,
                        "transfered" => $v->transfered,
                        "date" => $date
                    ); 
            }
            foreach ($result as & $v) {
                $total = 0.0;
                if (is_array($v)) {
                    foreach ($v as $key => $value) {
                        $total += $value["count"] * $value["sale_price"];
                    }
                }
                $v["offline"] = $total;

                $online = $this->getOnlineByTimeAndDinerId(array("time" => $v[0]["created"], "dinerId" => $v[0]["diner_id"]));
                $online = $online === null ? 0.0 : $online;
                $v["online"] = $online;

            }


            return $result;
        } else {
            return array();
        }
    }    


    /**
     * 取得提报列表
     */
    public function getItemList($args = array())
    {
        $list = $this->model->getItemList($args);
        if ($list === false) {
            return $this->export(array('code' => 500));
        }
        return $this->export(array('content' => $list));
    }

    /**
     * 判断当前日期是否有提报(manager)
     */
    public function isExisting()
    {
        return $this->model->isExisting($this->user->dinerId) === false ? false : true;
    }

    /**
     * 取出当日的线上销售额
     */
    public function getOnlineByTimeAndDinerId($args = array())
    {
        return $this->model->getOnlineByTimeAndDinerId($args['time'], $args['dinerId']);
    }

    /**
     * 将当前经营者的餐车餐品取出
     */
    public function getFoodByUser()
    {
        if ($this->user->type === "manager") {
            $food = $this->model->getItemByDinerId($this->user->dinerId);
        } else {
            // 为商户
            return false;
        }
        return $this->export(array("content" => $food));
    }

    /**
     * 添加
     */
    public function add()
    {
        $time = time();
        $diner_id = $this->user->dinerId;

        
        $result = $this->call(
            'api/merchant/dishRelation/getCityDinerDish',
            array(
                'fields' => 'dish.id, dish.food_name, dish.sale_price, dish.revision_id',
                'diner_id' => $this->user->dinerId
            )
        );
        $foods = array();
        foreach ($result['content'] as $item) {
            $foods[$item->id] = $item;
        }
        $datas = $_POST;
        $data = array();
        
        foreach ($datas['count'] as $key => $value) {
            $data[] = array(
                "diner_id" => $diner_id,
                "dish_reversion_id" => $foods[$datas['index'][$key]]->revision_id,
                "count" => $value,
                "created" => $time
            );
        }
        $result = $this->model->add($data);
        if ($result === false) {
            $err = $this->db->sth->errorInfo();
            return $this->export(array(
                'code' => 500,
                'message' => $err[2]
            ));
        }
        // 进行提报转账
        $result = $this->transfer(array("time" => $time));
        $message = $result['message'] === '转账成功' ? '添加并转账成功' : '添加成功，转账失败';
        return $this->export(array('content' => $result, 'message' => $message));
    }

    /**
     * 转账
     */
    public function transfer($args = array())
    {
        $time = $args['time'];
        $diner_id = $this->user->dinerId;
        $start = mktime(0, 0, 0, date('m', $time), date('d', $time), date('Y', $time));
        $end = mktime(0, 0, 0, date('m', $time), date('d', $time) + 1, date('Y', $time)) - 1;
        $result = $this->call("api/fund/report/getItemList", array(
            'diner_id' => $diner_id, 
            'start' => $start,
            'end' => $end
        ));
        if ($result['code'] != 200) {
            $this->error('数据库查询失败！', 'error');
        }
        $list = $result["content"]["list"];
        
        $result = $this->call("api/fund/report/translate", array('list' => $list));

        $result = array_values($result);
        $result = $result[0];
        
        $offline = $result['offline'];
        // 调用转账接口
        if ($result[0]["transfered"] == 0) {
            $result = $this->call("api/fund/fund/transferAccounts", array("variationType" => 15, "amount" => $offline, "diner_id" => $this->user->dinerId));
            if ($result['code'] == 200) {
                // 转账成功！修改数据库
                $result = $this->model->transfer($diner_id, $start, $end);
                return $this->export(array('content' => $result, 'message' => '转账成功'));
            } else {
                return $this->export(array('code' => 400, 'message' => $result['message']));
            }
        } else {
            return $this->export(array('code' => 500, 'message' => '提报已经转账'));
        }
    }

    /**
     * 删除
     */
    public function delete($args)
    {
        return $this->model->delete($args['ids']);
    }
}