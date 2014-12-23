<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Db;

use \PDO, 
    System\Loader;

abstract class Db {

    /**
     * Connect to database.
     * 
     * @param array $params
     */
    abstract public function connect($params);
    
    /**
     * Prefix a table name.
     * 
     * @param string $table
     */
    abstract public function prefix($table);
    
    /**
     * Fetch one row.
     * 
     * @param string $sql
     * @param array $binds
     * @param int $fetch_style
     * @return mixed data
     */
    abstract public function fetch($sql, $binds = array(), $fetch_style = PDO::FETCH_ASSOC);
    
    /**
     * Fetch all rows.
     * 
     * @param string $sql The SQL statement with placeholders..
     * @param array $bind An array of data to bind to the placeholders.
     * @param int $fetch_style PDO::FETCH_ASSOC|PDO::FETCH_CLASS|...
     * @return mixed data
     */
    abstract public function fetchAll($sql, $bind = array(), $fetch_style = PDO::FETCH_ASSOC);
    
    /**
     * Pager query.
     * 
     * @param string $sql
     * @param array $params Pager params.
     * @param array $binds
     * @param array $params
     * @return mixed data
     */
    abstract public function pagerQuery($sql,  $params = array(),  $binds = array(), 
        $fetch_style = PDO::FETCH_ASSOC);
}