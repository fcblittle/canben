<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Locale;

use System\Loader;

class Translator {
    
    /**
     * Translator options.
     * @var array
     */
    private static $_options = array();
    
    /**
     * Cached resources.
     * @var array
     */
    private static $_resources = array();
    
    /**
     * Set translator options.
     */
    public static function setOptions(array $options) {
        self::$_options = $options;
    }
    
    /**
     * Translator
     * 
     * System will find such file in path:
     * PATH_TO_LANGUAGE/[language]/[domain].php
     * 
     * @param string $text
     * @param array $args
     * @param string $domain Domain prefix
     * @param $language Language code
     */
    public static function translate($text, $args = array(), $domain = '', $language = '') {
        $language = $language ?: Locale::getLanguage();
        $resource = self::getResources($language, $domain);
        $string = (empty($resource) || ! isset($resource[$text])) ? $text : $resource[$text];
    
        return strtr($string, $args);
    }
    
    /**
     * Get language resource
     * 
     * For example:
     *     /Application/Resource/languages/zh/user.php
     * 
     * @param string $language Language code
     * @param string Domain prefix
     * @return array Language resource.
     */
    public static function getResources($language, $domain = '') {
        $domain = $domain ?: $language;
        $base = 'Resource/languages/' . $language . '/' . $domain;
        if (! isset(self::$_resources[$base])) {
            $resource = array();
            // Find system resource.
            $resource = Loader::load('System/' . $base) ?: $resource;
            // Find instance resource.
            if ($_resource = Loader::load('Instance/' . $base)) {
                $resource = array_merge($resource, $_resource);
            }
            // Find module resource.
            if (defined('MODULE_ROOT') && $_resource = Loader::load('Module/' . $base)) {
                $resource = array_merge($resource, $_resource);
            }
            self::$_resources[$base] = $resource;
        }
        
        return self::$_resources[$base];
    }
}
