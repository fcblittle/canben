<?php

namespace Module\Fund\Controller\Api;

use Application\Controller\AppApi;

class Purchase extends AppApi
{
    public function init()
    {
        $this->model = $this->model(':Purchase');
    }

    public function getItems($args)
    {
        $diner_ids = '';
        if(is_array($args['diner_ids']))
        {
            $diner_ids = implode(",", $args['diner_ids']);
            $diner_ids = rtrim($diner_ids,",");
        }
        if($diner_ids)
        {
            $args['diner_ids'] = $diner_ids;
        }
        
        $args['merchant_id'] = $this->user->id;
        
        $this->formatData($args);
        
        $result = $this->model->getItems($args);
        
        if ($result === false) {
            return $this->export(array(
                'code' => 500
            ));
        }
        
        return $this->export(array(
            'code'    => 200,
            'content' => $result
        ));
    }


    private function formatData(& $data) {
        if ($data['start']) {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $data['start']);
            $data['start'] = $date->getTimestamp();
        }
        if ($data['end']) {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $data['end']);
            $data['end'] = $date->getTimestamp();
        }
    }

    /**
     * 更新商家进货表foodcar_merchant_order
     */
    public function update($args) {
        
        if($args['ids'])
        {
            $ids = $args['ids'];
        }
        
        $result = $this->model->update($ids);
        
        if ($result === false) {
            return $this->export(array(
                'code' => 500,
                'message' => $this->db->sth->errorInfo()
            ));
        }
        return $this->export(array(
            'code'    => 200,
            'content' => $result
        ));
    }


     public function send($args) {
        
        if($args)
        {
            $ids = $args['ids'];
            $time_send = $args['time_send'];
        }
        $result = $this->model->send($ids,$time_send);
        
        if ($result === false) {
            return $this->export(array(
                'code' => 500,
                'message' => $this->db->sth->errorInfo()
            ));
        }
        return $this->export(array(
            'code'    => 200,
            'content' => $result
        ));
    }
    
}