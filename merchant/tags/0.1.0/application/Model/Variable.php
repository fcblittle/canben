<?php

namespace Application\Model;

use System\Model;

class Variable extends Model {
    
    /**
     * 获取系统变量
     */
    public function get($name) {
        $sql = "SELECT `value` "
            . " FROM {variable}"
            . " WHERE name = ? "
            . " LIMIT 1";
        $result = $this->db->fetch($sql, array($name));
        
        return isset($result->value) ? unserialize($result->value) : '';
    }
    
    /**
     * 设置系统变量
     */
    public function set($name, $value = NULL) {
        $sql = "REPLACE INTO {variable} (name, value)"
            . " VALUES (?, ?)";
        $binds = array($name, serialize($value));
        
        return $this->db->execute($sql, $binds);
    }

}