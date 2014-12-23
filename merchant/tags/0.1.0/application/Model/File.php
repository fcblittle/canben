<?php

namespace Application\Model;

use System\Model;

class File extends Model {
    
    public function add($data) {
        $sql = "INSERT INTO {file} "
            . " (`uid`, `name`, `bucket`, `key`, `mime`, `meta`, `status`, `timestamp`)"
            . " VALUES (?, ?, ?, ?, ?, ?,?, ?)";
        $binds = array(
            $data['uid'] ?: 0, 
            $data['name'] ?: '', 
            $data['bucket'] ?: '',
            $data['key'] ?: '',
            $data['mime'] ?: 0, 
            $data['meta'] ? serialize($data['meta']) : 0,
            $data['status'] ?: 0,
            $data['timestamp'] ?: REQUEST_TIME
        );
        if ($this->db->execute($sql, $binds)) {
            return $this->db->lastInsertId();
        } else {
            return FALSE;
        }
    }
    
    /**
     * 更新
     */
    public function update($fid, $data) {
        foreach ($data as $key => $value) {
            $sets[] = "{$key} = ?";
            $binds[] = $value;
        }
        $sets = implode(',', $sets);
        $binds[] = $fid;
        $sql = "UPDATE {file} SET {$sets} WHERE fid = ?";
        return $this->db->execute($sql, $binds);
    }
    
    /**
     * 更新
     */
    public function updateItemByKey($key, $data) {
        foreach ($data as $key => $value) {
            $sets[] = "{$key} = ?";
            $binds[] = $value;
        }
        $sets = implode(',', $sets);
        $binds[] = $key;
        $sql = "UPDATE {file} SET {$sets} WHERE `key` = ?";
        return $this->db->execute($sql, $binds);
    }
    
    /**
     * 根据key获取文件
     */
    public function getItemByKey($key, $fields = '*') {
        $sql = "SELECT {$fields} FROM {file} WHERE `key` = ? LIMIT 1";
        $item = $this->db->fetch($sql, array($key));
        if (! empty($item) && isset($item->meta)) {
            $item->meta = unserialize($item->meta);
        }

        return $item;
    }
    
    public function getMultiple(array $fid, $fields = '*') {
        $fids = implode(',', $fid);
        $sql = "SELECT {$fields} FROM {file}"
            . " WHERE fid IN ({$fids})";
        $items = $this->db->fetchAll($sql);
        if ($items) {
            foreach ($items as $item) {
                $list[$item->fid] = $item;
            }
        }
        return $list ?: array();
    }
    
    /**
     * 删除文件
     */
    public function deleteByFid($uid, $fid) {
        $sql = "DELETE FROM {file} WHERE fid = ? AND uid = ? LIMIT 1";
                
        return $this->db->execute($sql, array($fid, $uid));
    }
    
    /**
     * 删除文件
     */
    public function deleteByKey($uid, $key) {
        $sql = "DELETE FROM {file} WHERE `key` = ? AND uid = ? LIMIT 1";
                
        return $this->db->execute($sql, array($key, $uid));
    }
    
    /**
     * 批量删除
     */
    public function deleteMultiple($fids) {
        $fids = implode(',', $fids);
        $sql = "DELETE FROM file WHERE fid IN ({$fids})";
        
        return $this->db->execute($sql);
    }
    
    /**
     * 获取过期的文件
     */
    public function loadExpired($bucket, $rows = 500) {
        $sql = "SELECT fid,name FROM file
                WHERE created < UNIX_TIMESTAMP() - 86400
                LIMIT {$rows}";
        
        return $this->db->fetchAll($sql, array(), \PDO::FETCH_OBJ); 
    }
}