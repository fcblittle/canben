<?php

/**
 * xframework - 敏捷高效的php框架
 *
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System;

/**
 * 自动加载器
 *
 * 根据命名空间查找相应文件并载入，可以自动加载类文件、手动加载普通文件等。
 *
 * @author xlight <i@im87.cn>
 */
class Loader {

    /**
     * Cache for instances and files.
     * @var array
     */
    private static $cache   = array();

    /**
     * Namespace map.
     * @var array
     */
    private static $namespaces = array();

    /**
     * Register a namespace.
     *
     * @param mixed $namespace
     * @param string $dir
     */
    public static function registerNamespace($namespace, $dir = '') {
        if (is_array($namespace)) {
            self::$namespaces = array_merge(self::$namespaces, $namespace);
        } else {
            self::$namespaces[$namespace] = $dir;
        }
    }

    public static function getNamespacePath($namespace) {
        return self::$namespaces[$namespace];
    }

    /**
     * Autoload classes via spl_autoload.
     *
     * Autoload here only works for components.
     *
     * @param string $path Path name.
     * @return bool
     */
    public static function autoload($path) {
        require self::autoloadFind($path);
    }

    /**
     * Throw exception here instead of that in autoload()
     *
     * @param $path
     */
    private static function autoloadFind($path) {
        if ($file = self::find($path)) {
            return $file;
        }
        throw new Exception(t('Class file \'@class\' does not exist.',
            array('@class' => $path)), 500);
    }

    /**
     * Scan for class files.
     *
     * @param string $path Application\Controller\Home etc.
     * @return string|bool
     */
    public static function find($path, $extension = '.php') {
        $cached = $path . $extension;
        if (! isset(self::$cache[$cached])) {
            self::$cache[$cached] = false;
            $path = str_replace(array('\\', '\\\\'), '/', $path);
            if (substr($path, 0, 1) === SEP) {
                $path = substr($path, 1);
            }
            foreach (self::$namespaces as $ns => $v) {
                // 反斜线会影响正则匹配
                $ns = str_replace(array('\\', '\\\\'), '/', $ns);
                $v = str_replace(array('\\', '\\\\'), '/', $v);
                if (preg_match("#^{$ns}#i", $path)) {
                    $file = preg_replace("#^{$ns}#i", $v, $path) . $extension;
                    $file = str_replace(array('/', '\\'), SEP, $file);
                    self::$cache[$cached] = file_exists($file) ? $file : false;
                    break;
                }
            }
        }

        return self::$cache[$cached];
    }

    /**
     * 加载文件
     *
     * @param string $path 文件路径
     * @param string $extenstion 文件扩展名
     * @param array $variables 要导入的变量
     * @return mixed
     */
    public static function load($path, $extenstion = '.php', $variables = array()) {
        if ($file = self::find($path, $extenstion)) {
            if ($variables) {
                extract($variables, EXTR_REFS);
            }
            return require_once $file;
        }
        return false;
    }

    /**
     * __call()
     */
    public function __call($name, $args) {
        throw new Exception(t('Call to undefined method: @name',
            array('@name' => $name)));
    }

    /**
     * __callStatic()
     */
    public static function __callStatic($name, $args) {
        throw new Exception(t('Call to undefined static method: @name',
            array('@name' => $name)));
    }

}