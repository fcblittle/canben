<?php

namespace Module\Fund\Model;

use System\Model;

class Shipping extends Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = '`foodcar_merchant_shipping`';
       
    }

    /**
     * 添加
     */
    public function add($data = array())
    {  
        $data = array_merge(array(
            'ids' => '',
            'shipping_time' => 0
        ), $data);

        $data['ids'] = explode(',', $data['ids']);
        
        if(is_array($data['ids']))
        {
            $binds = array();
            foreach ($data['ids'] as $v) {
                $v = intval($v);
                $binds[] = "({$v}, {$data['shipping_time']})";
            }
            $binds = implode(',', $binds);
            //var_dump($binds);
        }else{
            $binds = "({$data['ids']}, {$data['shipping_time']})";
            //var_dump($binds);
        }
        

        $sql = "INSERT INTO {$this->table}(`order_id`, `shipping_time`)
                VALUES {$binds}";
        //var_dump($sql);
        return $this->db->execute($sql) === false ? false : $this->db->lastInsertId();
    }

    public function getItems($args)
    {
        $binds = array();
        if($args['ids'])
        {
            $binds[] = $args['ids'];
        }
        if(strpos($args['ids'],','))
        {
            $sql = "SELECT * FROM {$this->table}
                WHERE `order_id` IN (?)";
        }
        else
        {
            $sql = "SELECT * FROM {$this->table}
                WHERE `order_id` = ?";
        }
        $result=$this->db->fetchAll($sql,$binds);
        
        //var_dump($result);die;
        return $result;
        
    }

}