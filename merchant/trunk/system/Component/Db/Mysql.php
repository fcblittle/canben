<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Db;

use \PDO;
use System\Exception;

/**
 * PDO mysql driver extension.
 * 
 * @author xlight <i@im87.cn>
 */
class Mysql {
    
    /**
     * PDO instance.
     * @var object
     */
    private $dbh        = NULL;
    
    /**
     * PDO statement.
     * @var object
     */
    public $sth         = NULL;
    
    /**
     * Is connected?
     * @var bool
     */
    public $connected    = false;
    
    /**
     * Query counts.
     * @var int
     */
    public $queries      = 0;
    
    /**
     * Current SQL.
     * @var string
     */
    public $sql          = '';
    
    /**
     * Mysql parameters
     * @var array
     */
    private $params     = array(
        'fetchStyle' => \PDO::FETCH_OBJ
    );
    
    /**
     * constructor init.
     * 
     * @param array $config.
     */
    function __construct($params = array()) {
        $this->params = merge_options($this->params, $params);
        $this->connect($this->params);
    }

    /**
     * connect.
     * 
     * @param array $params
     * @return object PDO object
     */
    public function connect($params = array()) {
        if ( !isset($this->dbh) ) {
            $this->dbh = new PDO(
                "mysql:host={$params['hostname']};dbname={$params['database']}", 
                $params['username'], 
                $params['password'], 
                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
            );
            $this->connected = TRUE;
        }
        return $this;
    }
    
    /**
     * prefix table.
     * 
     * @param string $table
     */
    public function prefix($table) {
        return $this->params['prefix'] . $table;
    }
    
    /**
     * PDO::query()
     * 
     * @param string $sql
     * @param ...
     */
    public function query() {
        $args = func_get_args();
        $statement = $this->preprocess($args[0]);
        $args = array($statement) + $args;

        return call_user_func_array(array($this->dbh, 'query'), $args);
    }
    
    /**
     * PDO::exec()
     */
    public function exec($statement) {
        return $this->dbh->exec($this->preprocess($statement));
    }
    
    /**
     * PDO::prepare()
     */
    public function prepare($statement, $driver_options = array()) {
        $this->sth = $this->dbh->prepare($this->preprocess($statement), $driver_options);
        if ($this->sth === false) {
            $error = $this->dbh->errorInfo();
            throw new Exception($error[2], $error[1]);
        }
        return $this->sth;
    }
    
    /**
     * Prepare a SQL to use.
     * 
     * @param string $sql
     * @return string
     */
    public function preprocess($sql) {
        $this->queries++;
        $this->sql = preg_replace('#\{([^\}]*)\}#', $this->prefix('$1'), $sql);

        return $this->sql;
    }
    
    
    /**
     * Fetch all rows.
     * 
     * @param string $sql The SQL statement with placeholders..
     * @param array $bind An array of data to bind to the placeholders.
     * @param int $fetch_style PDO::FETCH_ASSOC|PDO::FETCH_CLASS|...
     * @return mixed data
     */
    public function fetchAll($sql, $bind = array(), $fetch_style = null) {
        if (! $fetch_style) {
            $fetch_style = $this->params['fetchStyle'] ?: PDO::FETCH_OBJ;
        }
        if ($this->sth = $this->prepare($sql)) {
            empty($bind) ? $this->sth->execute() : $this->sth->execute($bind);
            if ($this->sth->errorCode() !== '00000') {
                return false;
            }
            return $this->sth->fetchAll($fetch_style);
        }
    }
    
    /**
     * Fetch one row.
     * 
     * @param string $sql
     * @param array $binds
     * @param int $fetch_style
     * @return mixed data
     */
    public function fetch($sql, $binds = array(), $fetch_style = null) {
        if (! $fetch_style) {
            $fetch_style = $this->params['fetchStyle'] ?: PDO::FETCH_OBJ;
        }
        if ($this->sth = $this->prepare($sql)) {
            empty($binds) ? $this->sth->execute() : $this->sth->execute($binds);
            if ($this->sth->errorCode() !== '00000') {
                return false;
            }
            $result = $this->sth->fetch($fetch_style);
            // 有些环境无法自动释放游标
            $this->sth->closeCursor();
            return $result;
        }
    }
    
    /**
     * Pager query.
     * 
     * @param string $sql
     * @param array $params Pager params.
     * @param array $binds
     * @param array $params
     * @return mixed data
     */
    public function pagerQuery($sql, $params = array(), $binds = array(), 
        $fetch_style = null) {
        if (! $fetch_style) {
            $fetch_style = $this->params['fetchStyle'] ?: PDO::FETCH_OBJ;
        }
        $params = merge_options(array(
            'page'  => 0,
            'limit' => 10
        ), $params);
        $sql .= " LIMIT " . $params['page'] * $params['limit'] . ",{$params['limit']}";

        return $this->fetchAll($sql, $binds, $fetch_style);
    }
    
    /**
     * Execute a SQL statement with bind values.
     * 
     * @param string $sql
     * @param array $binds
     * @return int affected rows.
     */
    public function execute($sql, $binds = array()) {
        if ($this->sth = $this->prepare($sql)) {
            empty($binds) ? $this->sth->execute() : $this->sth->execute($binds);
            if ($this->sth->errorCode() !== '00000') {
                return false;
            }
            return $this->sth->rowCount();
        }
    }
    
    public function queryErrorInfo() {
        return $this->sth->errorInfo();
    }
    
    /**
     * 单表快速插入记录
     *
     * 单条记录：
     *   $data = array('field' => 'value')
     * 多条记录：
     *   $data = array(array('field' => 'value'), array('field' => 'value'))
     *
     * @param string $table
     * @param array $data
     * @return int
     */
    public function insert($table, array $data) {
        $fields = $values = $binds = array();
        if (! is_int(key($data))) {
            $data = array($data);
        }
        foreach ($data as $k => $v) {
            $value = array();
            foreach ($v as $k1 => $v1) {
                if ($k == 0) {
                    $fields[] = "`{$k1}`";
                }
                $value[] = '?';
                $binds[] = $v1;
            }
            $values[] = '(' . implode(',', $value) . ')';
        }
        $values = implode(',', $values);
        $fields = implode(',', $fields);
        $sql = "INSERT INTO {$table} "
            . "({$fields}) VALUES {$values}";

        return $this->execute($sql, $binds) === false ? false : $this->lastInsertId();
    }
    
    /**
     * 单表便捷更新
     * 
     * @param string $table
     * @param array $data
     * @param array $conditions
     * @param array $orders
     * @param int $limit
     * @return int|bool
     */
    public function update(
        $table,
        array $data,
        array $conditions = array(),
        array $orders = array(),
        $limit = 0
    ) {
        $binds = $conds = $sets = array();

        // sets
        if (empty($data)) {
            return false;
        }
        foreach ($data as $field => $val) {
            $isExp = preg_match('#\{(.+)\}#', $val, $m);
            $sets[] = $isExp ? "{$field} = {$m[1]}" : "{$field} = ?";
            if (! $isExp) {
                $binds[] = $val;
            }
        }
        $sets = implode(',', $sets);

        // conditions
        if (! empty($conditions)) {
            $conds =  ' WHERE ' . $conditions[0];
            if (isset($conditions[1])) {
                $binds = array_merge($binds, $conditions[1]);
            }
        }

        // orders
        $orders = $orders ? ' ORDER BY ' . implode(',', $orders) : '';

        // limit
        $limit = $limit ? ' LIMIT ' . $limit : '';

        $sql = "UPDATE {$table} "
            . " SET {$sets} "
            . " {$conds} {$orders} {$limit}";

        return $this->execute($sql, $binds);
    }
    
    /**
     * PDO::lastInsertId()
     *
     * @param mixed $name
     * @return int
     */
    public function lastInsertId($name = NULL) {
        return $this->dbh->lastInsertId($name);
    }
    
    /**
     * Count rows.
     * 
     * @param string $sql
     * @return int
     */
    public function rowsCount($sql) {
        $this->sth = $this->query($sql);
        return $this->sth->fetchColumn();
    }
    
    /**
     * PDO::errorInfo()
     * 
     * @return array
     */
    public function errorInfo() {
        return $this->dbh->errorInfo();
    }
    
    /**
     * PDO::beginTransaction()
     * 
     * @return bool
     */
    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }
    
    /**
     * PDO::commit()
     * 
     * @return bool
     */
    public function commit() {
        return $this->dbh->commit();
    }
    
    /**
     * PDO::rollBack()
     * 
     * @return bool
     */
    public function rollBack() {
        return $this->dbh->rollBack();
    }
    
    /**
     * Release PDO object
     */
    public function __destruct() {
        $this->dbh = NULL;
    }
}