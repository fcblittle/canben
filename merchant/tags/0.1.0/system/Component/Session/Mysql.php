<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Session;

use System\Bootstrap;

class Mysql {
    
    /**
     * Session config.
     * @var array
     */
    private $_config = array();
    
    /**
     * Constructor.
     * 
     * @param array $config
     */
    public function __construct(array $config) {
        $this->_config = $config;
        session_set_save_handler(
            array($this, 'open'), 
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'gc')
        );
        $this->db = Bootstrap::getGlobal('db');
        register_shutdown_function('session_write_close');
    }
    
    /**
     * Initialize session
     */
    public function open($save_path, $sessionid) {
        return TRUE;
    }
    
    /**
     * Read session data
     */
    public function read($sessionid) {
        $sql = "SELECT data FROM {$this->_config['table']}"
            . " WHERE sid = ? "
            . " LIMIT 1";
        $result = $this->db->fetch($sql, array($sessionid), \PDO::FETCH_OBJ);

        return isset($result) ? $result->data : NULL;
    }
    
    /**
     * Write session data
     */
    public function write($sessionid, $sessiondata) {
        // Insert.
        if(is_null($this->read($sessionid))) {
            $sql = "INSERT INTO {$this->_config['table']} "
                . " (sid, timestamp, data) VALUES (?, ?, ?)";

            return $this->db->execute($sql, array($sessionid, REQUEST_TIME, $sessiondata));
        }
        // Update.
        else{
            $sql = "UPDATE {$this->_config['table']}"
                . " SET timestamp = ?, data = ? "
                . " WHERE sid = ?"
                . " LIMIT 1";

            return $this->db->execute($sql, array(REQUEST_TIME, $sessiondata, $sessionid));
        }
    }
    
    /**
     * Close the session
     */
    public function close() {
        return TRUE;
    }
    
    /**
     * Cleanup old sessions
     */
    public function gc($maxlifetime) {
        $sql = "DELETE FROM {$this->_config['table']} "
            . " WHERE timestamp + {$maxlifetime} < " . REQUEST_TIME;
        
        return $this->db->execute($sql);
    }
    
    /**
     * Destroy a session
     */
    public function destroy($sessionid) {
        $sql = "DELETE FROM {$this->_config['table']} "
            . " WHERE sid = ? LIMIT 1";
        
        return $this->db->execute($sql, array($sessionid));
    }
}
