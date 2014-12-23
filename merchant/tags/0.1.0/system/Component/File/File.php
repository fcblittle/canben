<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\File;

class File {
    
    /**
     * File options.
     * @var array
     */
    private static $_options = array();
    
    /**
     * Set options.
     * 
     * @param array $options
     */
    public function setOptions($options = array()) {
        self::$_options = array_merge(self::$_options, $options);
    }
    
    /**
     * get file information.
     * 
     * @param string full filepath.
     * @return array array of information.
     */
    public static function getInfo($file) {
        $pathinfo = pathinfo($file);
        $size = filesize($file);
        
        return array(
            'name'         => $pathinfo['filename'],
            'size'         => $size,
            'extension'    => $pathinfo['extension'],
            'mime'         => self::getMime($file)
        );
    }
    
    /**
     * 获取文件mime信息
     * 
     * @param string $file 文件路径
     * @return string
     */
    public static function getMime($file) {
        $finfo = new finfo(FILEINFO_MIME, '');
        return $finfo->file($file);
    }
    
    /**
     * 递归创建文件夹
     * 
     * @param string $path 路径信息
     * @param int $mode 8进制
     */
    public static function mkdirs($path, $mode = 0755) {
        return @ mkdir($path, $mode, true);
    }
    
    /**
     * Create a file path
     * 
     * @param string $basename
     * @param string $dir
     * @return string
     */
    public function createPath($basename, $dir) {
        return '/' . self::$_options['dir'] . "/{$dir}/$basename";
    }
    
    public static function makeUniqueName($prefix = '') {
        return md5(uniqid($prefix . mt_rand()));
    }
    
    /**
     * get file extension.
     */
    public static function getExtension($file) {
        $pathinfo = pathinfo($file);
        
        return $pathinfo['extension'] ?: '';
    }
    
    /**
     * 递归删除文件夹
     * 
     * @param string $dir 要删除的文件夹
     */
    public static function rrmdir($dir) {
        if (! file_exists($dir)) {
            return true;
        } 
        $files = array_diff(scandir($dir), array('.', '..')); 
        foreach ($files as $file) { 
            (is_dir("{$dir}/{$file}")) 
                ? self::rrmdir("{$dir}/{$file}") 
                : unlink("{$dir}/{$file}"); 
        } 
        return rmdir($dir); 
    }
    
    /**
     * 递归复制文件(夹)
     * 
     * @param string $src 源路径
     * @param string $dst 目标路径
     */
    public static function rcopy($src, $dst) {
        if (! file_exists($src)) {
            return false;
        }
        if (file_exists($dst)) {
            self::rrmdir($dst);
        }
        if (is_dir($src)) {
            @ mkdir($dst);
            $files = scandir($src);
            foreach ($files as $file) {
                if ($file !== "." && $file !== "..") {
                    self::rcopy("$src/$file", "$dst/$file"); 
                }
            }
      } elseif (file_exists($src)) {
          copy($src, $dst);
      }
    }
    
    /**
     * 删除文件
     * 
     * @param string $file 文件路径
     * @return bool
     */
    public static function delete($file) {
        if (file_exists($file)) {
            @unlink($file);
        } else {
            return false;
        }
    }
}

