<?php

/**
 * xframework - 敏捷高效的php框架
 *
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Log;
use System\Bootstrap;
use System\Exception;

/**
 * MySQL日志记录
 * Class Mysql
 * @package System\Component\Log
 * @use System\Component\Db\Mysql
 */
class Mysql extends Logger {

    /**
     * 选项
     * @var array
     */
    private $options = array(
        'table'        => 'log',
        'defaultLevel' => 6
    );

    /**
     * 数据库连接
     * @var object
     */
    private $db = null;

    /**
     * 数据表
     * @var string
     */
    private $table = '';

    public function __construct($connection, array $options = array()) {
        $this->db = $connection;
        $this->setOptions($options);
    }

    /**
     * 设置选项
     * @param array $options
     * @return void
     */
    public function setOptions(array $options = array()) {
        $this->options = array_merge($this->options, $options);
        $this->table = $this->db->prefix($this->options['table']);
    }

    /**
     * 获取级别代码
     * @param string $name
     * @return int
     */
    public function getLevelCode($name = '') {
        return isset($this->levels[$name])
            ? $this->levels[$name]
            : $this->options['defaultLevel'];
    }

    /**
     * 记录日志
     * @param array $data
     * @return mixed
     */
    public function log(array $data = array()) {
        $data = array_merge(array(
            'type'    => '',
            'level'   => 6,
            'message' => '',
            'data'    => null,
            'timestamp' => time(),
            'microtime' => microtime(true)
        ), $data);
        $data['data'] = json_encode($data['data']);
        return $this->db->insert($this->table, $data);
    }

    /**
     * 初始化数据表
     * @throws \System\Exception
     */
    public function init() {
        $sql = file_get_contents(__DIR__ . SEP . 'Resource' . SEP . 'log.sql');
        $sql = str_replace('{table}', $this->table, $sql);
        $result = $this->db->execute($sql);
        if ($result === false) {
            $error = $this->db->sth->errorInfo();
            throw new Exception($error[2], $error[1]);
        }
    }
}