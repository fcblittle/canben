<?php

namespace Application\Model;

use System\Model;

class User extends Model {
    
    /**
     * 根据ID获取用户
     * 
     * @param int $uid 用户id
     * @param string $fields 查询的字段
     * @return 查询结果
     */
    public function getItemById($uid, $fields = '*') {
        $sql = "SELECT {$fields} FROM {user} WHERE uid = ? LIMIT 1";

        return $this->db->fetch($sql, array($uid));
    }
    
    /**
     * Get user by name.
     */
    public function getItemByName($name, $fileds = '*') {
        $sql = "SELECT {$fileds} FROM {user} WHERE name = ?";
        
        return $this->db->fetch($sql, array($name));
    }

    /**
     * Get user by email.
     */
    public function getItemByEmail($email, $fileds = "*") {
        $sql = "SELECT {$fileds} FROM {user} WHERE email = ? LIMIT 1";
        
        return $this->db->fetch($sql, array($email));
    }

    /**
     * Update user table.
     */
    public function update($uid, $data) {
        foreach ($data as $key => $value) {
            $sets[] = "{$key} = ?";
            $values[] = $value;
        }
        $sets = implode(',', $sets);
        $values[] = $uid;
        $sql = "UPDATE {user} SET {$sets} WHERE uid = ?";
        
        return $this->db->execute($sql, $values);
    }

    /**
     * Transaction: 注册用户
     */
    public function add($data) {
        $this->db->beginTransaction();
        // 添加用户
        $sql = "INSERT INTO {user} (name,pass,email,salt,created)"
            . " VALUES (?,?,?,?,?)";
        $binds = array(
            $data['name'], $data['hashedPass'],
            $data['email'], $data['salt'],
            $data['created']
        );
        $result = $this->db->execute($sql, $binds);
        if ($result === FALSE) {
            $this->db->rollBack();
            return FALSE;
        }
        $uid = $this->db->lastInsertId();
        // 添加用户资料
        $sql = "INSERT INTO {user_profile} (uid)VALUES({$uid})";
        if ($this->db->execute($sql) === FALSE) {
            $this->db->rollBack();
            return FALSE;
        }
        if ($this->db->commit()) {
            return $uid;
        }
    }
    
    /**
     * 获取角色
     */
    public function getRoleNames($uids, $fields = '*') {
        $uids = implode(',', $uids);
        $sql = "SELECT {$fields} FROM {user_role} ur"
            . " LEFT JOIN {role} r USING(rid)"
            . " WHERE uid IN ({$uids})";
        return $this->db->fetchAll($sql);
    }

}