<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Cache;

use System\Loader;

 /**
  * Cache component.
  * 
  * @author xlight <i@im87.cn>
  */
abstract class Cache {
    
    /**
     * Cache factory.
     */
    public static function factory(array $options) {
        $adapter = ucfirst($options['adapter']);
        $path = __NAMESPACE__ . "\\Adapter\\{$adapter}";
        if ($file = Loader::load($path)) {
            return new $path($options['params']);
        }
    }
    
    /**
     * Cache set.
     */
    abstract public function set($cid, $data, $expire = 0);
    
    /**
     * Cache get.
     */
    abstract public function get($cid);
    
    /**
     * Cache clear.
     */
    abstract public function clear();
    
    /**
     * Cache delete.
     */
    abstract public function delete($cid);
}
