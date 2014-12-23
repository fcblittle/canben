<?php

namespace Application\Model;

use System\Model;

class Event extends Model {
    
    /**
     * 获取触发器
     * 
     * @param string $event 事件名
     */
    public function getTriggersByEvent($event) {
        $sql = "SELECT * FROM `{trigger}`"
            . " WHERE event = ?";
        $result = $this->db->fetchAll($sql, array($event));
        return $result;
    }

}