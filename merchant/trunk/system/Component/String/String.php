<?php
 
/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\String;

class String {
    
    public function wordWrap() {
        
    }
    
    /**
     * 将字符串转为纯文本
     * 
     * @param string $string 输入字符串
     * @return string 转换后的字符串
     */
    public function getPlainText($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * 截取utf8字符
     * 
     * @param string $string 输入字符
     * @param int $start 开始截取位置
     * @param int $len 截取长度
     * @return string 截取的字符串
     */
    public function truncateUtf8($string, $start, $len) {
        return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $start . '}' . 
        '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $len . '}).*#s', 
        '$1', $string); 
    }
    
    /**
     * Generate a string representation for the given byte count.
     * 
     * @param int $size
     * @param string $language
     * @return string
     */
    public static function formatSize($size, $language = '') {
        if ($size < 1024) {
            return $size === 1 ? 
                t('1 byte', array(), $language) : 
                t('@size bytes', array('@size' => $size), $language);
        } else {
            $size = $size / 1024; // Convert bytes to kilobytes.
            $units = array(
                t('@size KB', array(), $language),
                t('@size MB', array(), $language),
                t('@size GB', array(), $language),
                t('@size TB', array(), $language),
                t('@size PB', array(), $language),
                t('@size EB', array(), $language),
                t('@size ZB', array(), $language),
                t('@size YB', array(), $language),
            );
            foreach ($units as $unit) {
                if (round($size, 2) >= 1024) {
                    $size = $size / 1024;
                } else {
                    break;
                }
            }
            return str_replace('@size', round($size, 2), $unit);
        }
    }
    
    public function formatDate() {
        
    }
}
