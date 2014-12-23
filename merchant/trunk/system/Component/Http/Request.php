<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Http;

use System\Bootstrap;

class Request {
    
    /**
     * Query path.
     * @var string
     */
    private $path       = '';
    
    /**
     * Client IP.
     * @var string
     */
    private static $_clientIP   = '';
    
    /**
     * Path arguments.
     * @var array
     */
    private $args       = array();
    
    /**
     * Query sections.
     * @var array
     */
    private $queries    = array();
    
    /**
     * Request options.
     * @var array
     */
    private static $options    = array();
    
    public function __construct($path = '') {
        if ($path !== '') {
            $this->setPath($path);
        }
    }
    
    /**
     * Set request options.
     * 
     * @param array $options
     */
    public function setOptions(array $options) {
        $this->options = $options;
    }
    
    /**
     * Get arguments in path.
     * 
     * @param int $index index of arguments.
     * @return mixed
     */
    public function arg($index = NULL) {
        if (empty($this->args)) {
            $this->args = $this->path ? explode('/', $this->path) : array();
        }
        if ($index === NULL) {
            return $this->args;
        }
        return isset($this->args[$index]) ? $this->args[$index] : '';
    }
    
    /**
     * Returns $_GET value.
     * 
     * @param string $name
     * @return string value
     */
    public function get($name = '') {
        if (! isset($this->queries[$name])) {
            if (! $q = $_SERVER['QUERY_STRING']) {
                return '';
            }
            $q = explode('&', $q);
            foreach ($q as $param) {
                $item = explode('=', $param);
                $this->queries[$item[0]] = $item[1];
            }
        }
        if ($name !== '') {
            return isset($this->queries[$name]) ? $this->queries[$name] : '';
        } else {
            return $this->queries;
        }
    }
    
    /**
     * Get queires except 'p' query.
     * 
     * @return array
     */
    public function getQueries() {
        $queries = $this->queries;
        unset($queries['p']);

        return $queries;
    }
    
    /**
     * Set queires except 'p' query.
     * 
     * @param string $q
     */
    public static function setQueries($queries) {
        $q = explode('&', $queries);
        foreach ($q as $param) {
            $item = explode('=', $param);
            $this->queries[$item[0]] = $item[1];
        }
    }
    
    /**
     * Get 'post' value.
     * 
     * @param string $name
     * @return mixed value
     */
    public static function post($name) {
        return $_POST[$name];
    }
    
    /**
     * Return host with protocol.
     * 
     * @return string
     */
    public static function baseUrl() {
        if (! isset(self::$options['baseUrl']) || self::$options['baseUrl'] === '') {
            self::$options['baseUrl'] = self::protocol() . $_SERVER['HTTP_HOST'];
        }
        
        return self::$options['baseUrl'];
    }
    
    /**
     * Return protocol.
     * 
     * @return string
     */
    public static function protocol() {
        return (stripos($_SERVER['SERVER_PROTOCOL'], 'https') ? 'https://' : 'http://');
    }
    
    /**
     * Return href,similar with js' location.href
     * 
     * @return string
     */
    public static function href() {
        return self::protocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Get site base domain.
     * 
     * You can set baseDomain in config.php:Http
     * 
     * @return string
     */
    public static function baseDomain() {
        if (! isset($this->options['baseDomain'])) {
            $pieces = array_slice(explode('.', $_SERVER['HTTP_HOST']), -2, 2);
            $this->options['baseDomain'] = implode('.', $pieces);
        }

        return $this->options['baseDomain'];
    }
    
    /**
     * Get http referer url.
     * 
     * @return string
     */
    public static function referer() {
        return $_SERVER['HTTP_REFERER'];
    }
    
    /**
     * Get query path.
     * 
     * @return string user/login,etc.
     */
    public function getPath() {
        if ($this->path === '') {
            $this->path = self::get('p');
        }
        
        return $this->path;
    }

    public function getRawPath() {
        return isset($_GET['p']) ? $_GET['p'] : '';
    }
    
    /**
     * Set real path.
     * 
     * @param string $path
     */
    public function setPath($path) {
        $this->path = $path;
        if (strpos($path, '?')) {
            list($path, $queries) = explode('?', $path);
            $this->setQueries($queries);
        }
        // Reset args.
        $this->args = array();
    }
    
    public function isPathActive($path = '') {
        if ($this->path === $path) {
            return TRUE;
        }
        $aliases = \System\Bootstrap::getAliases();
        foreach ($aliases as $alias => $target) {
            if (preg_match('#^' . $alias . '$#', $path)) {
                return TRUE;
            } 
        }
        return FALSE;
    }
    
    /**
     * Returns query string.
     * 
     * @return string 
     */
    public function query() {
        return $_SERVER['QUERY_STRING'];
    }
    
    
    /**
     * Get client ip.
     * 
     * @return string Client IP address.
     */
    public static function clientIP() {
        if (! self::$_clientIP) {
            if (! empty($_SERVER["HTTP_CLIENT_IP"])) {
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            }
            if (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
                if ($ip) {
                    array_unshift($ips, $ip); 
                    $ip = FALSE; 
                }
                for ($i = 0; $i < count($ips); $i++) {
                    if (!preg_match("/^(10|172.16|192.168)./", $ips[$i])) {
                        $ip = $ips[$i];
                        break;
                    }
                }
            }
            $ip = $ip ? $ip : $_SERVER['REMOTE_ADDR'];
            self::$_clientIP = $ip;
        }

        return self::$_clientIP;
    }
    
    /**
     * Check https.
     * 
     * @return bool
     */
    public static function isHttps() {
        return isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on';
    }
    
    /**
     * Get url info
     * 
     * @return array url info.
     */
    public static function urlInfo() {
        $q = explode('&', $_SERVER['QUERY_STRING']);
        $params = array(); 
        foreach ($q as $param) { 
            $item = explode('=', $param); 
            $params[$item[0]] = $item[1]; 
        } 
        $path = $params['p'];
        unset($params['p']);
        
        return array(
            'path' => $path,
            'query' => $params
        );
    }
        
}
