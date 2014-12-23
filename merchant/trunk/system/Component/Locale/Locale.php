<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Locale;

use System\Bootstrap,
    System\Component\Http\Request;

class Locale {
    
    /**
     * Locale options.
     * @var array
     */
    private static $_options = array(
        'queryString'     => 'l',
        'defaultLanguage' => 'en'
    );
    
    /**
     * Current language.
     * @var string
     */
    private static $_language = '';
    
    /**
     * Set locale options.
     * 
     * @param array $options
     */
    public static function setOptions(array $options) {
        self::$_options = array_merge(self::$_options, $options);
    }
    
    /**
     * Get current language.
     * 
     * @return string language code
     */
    public static function getLanguage() {
        if (self::$_language === '') {
            $config = Bootstrap::getConfig();
            $queryString = self::$_options['queryString'];
            self::$_language = isset($_GET[$queryString])
                ? $_GET[$queryString]
                : self::$_options['defaultLanguage'];
        }
        
        return self::$_language;
    }
}
