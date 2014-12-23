<?php

namespace Module\Fund\Model;

use System\Model;
use Application\Controller\Account;

class Purchase extends Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = '`foodcar_merchant_order`';
        $this->tableDetail = '`foodcar_merchant_order_detail`';
        $this->tableDiner = '`foodcar_diner`';
        $this->tableStaff = '`biz_staff`';
    }

    /**
     * 获取该商户所有全部采购订单
     */
    public function getItems($args)
    {
        $params = array_merge(array(
            'fields'      => 'o.*,a.diner_name,b.realname,c.dish_id',
            'order'   => 'o.id DESC',
            'merchant_id' => null,
            'diner_id'  => '-1',
            'start' => '',
            'end' => '',
            'status' => '-1',
            'diner_ids' => ''
            //'role' => 1
        ), $args);
        //var_dump($params);
       
        $binds = array();

        //$binds[]=$params['diner_id'];

        if ($params['diner_id'] != '-1')
        {
            $cond .= " AND o.diner_id = ?";
            $binds[] = $params['diner_id'];
        } 
        else
        {
            $cond .= " AND o.merchant_id = ?";
            $binds[] = $params['merchant_id'];
        }

        if($params['start'])
        {
            $cond .= " AND o.created > ? ";
            $binds[] = $params['start'];
        }
        if($params['end'])
        {
            $cond .= " AND o.created < ? ";
            $binds[] = $params['end'];
        }
        if ($params['status'] != '-1')
        {
            $cond .= " AND o.status = ? ";
            $binds[] = $params['status'];
        }
        
        if($params['diner_ids'])
        {
            $cond .= " AND o.diner_id IN ({$params['diner_ids']})";
            
        }
       
        $sql = "SELECT {$params['fields']}"
            . " FROM {$this->table} AS o"
            . " INNER JOIN {$this->tableDiner} AS a ON o.diner_id = a.id"
            . " INNER JOIN {$this->tableStaff} AS b ON o.diner_id = b.diner_id"
            . " INNER JOIN {$this->tableDetail} AS c ON o.id = c.order_id"
            . " WHERE b.role=1 AND b.status != -1 {$cond}"
            . " ORDER BY {$params['order']}";
        
        $result=$this->db->fetchAll($sql,$binds);
        
        return $result;
    }

    /**
     * 更新
     */
    public function update($ids)
    {
        $time_paid = time();
        if(strpos($ids,','))
        {
            $sql = "UPDATE {$this->table}
                SET `time_paid` = {$time_paid}
                WHERE `id` IN ({$ids})";
        }
        else
        {
            $sql = "UPDATE {$this->table}
                SET `time_paid` = {$time_paid}
                WHERE `id` = {$ids}";
        }
        //var_dump($sql);
        return $this->db->execute($sql);
    }
    

    public function send($ids,$time_send)
    {
        //var_dump($ids);
        //var_dump($time_send);die;
        if($ids && $time_send)
        {
            if(strpos($ids,','))
            {
                $sql = "UPDATE {$this->table}
                SET `status` = 2,`time_send` = {$time_send}
                WHERE `id` IN ({$ids})";
            }
            else
            {
                $sql = "UPDATE {$this->table}
                SET `status` = 2,`time_send` = {$time_send}
                WHERE `id` = {$ids}";
            }
        }   
        //var_dump($sql);
        return $this->db->execute($sql);
        
        
    }
    
}