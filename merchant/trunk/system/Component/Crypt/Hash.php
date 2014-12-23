<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Crypt;

use System\Bootstrap;

class Hash {
    
    /**
     * Calculates a base-64 encoded, URL-safe sha-256 hmac.
     * 
     * @see drupal's drupal_hmac_base64()
     * @param string $data
     * @param string $key
     */
    public static function base64Hmac($data, $key) {
        $hmac = base64_encode(hash_hmac('sha256', $data, $key, TRUE));
        // Modify the hmac so it's safe to use in URLs.
        return strtr($hmac, array('+' => '-', '/' => '_', '=' => ''));
    }
    
    /**
     * Generate a password string via salt
     * 
     * @param string $string
     * @param string $salt
     * @return string
     */
    public static function password($string, $salt = '') {
        $salt = $salt ?: self::randomString(6);
        
        return md5(hash('sha256', $string) . $salt);
    }
    
    /**
     * Generate a random string.
     * 
     * @param int $length
     * @return string
     */
    public static function randomString($length = 6, $chars = '') {
        if ($chars === '') {
            $chars  = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $chars .= '0123456789';
            $chars .= '!@#$%^&*()-_[]{}<>~+=,.;:/?|';
        }
        $string = '';  
        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[mt_rand(0, strlen($chars) - 1)];
        }  

        return $string;  
    } 
    
    /**
     * 将整数转为短字符串
     * 
     * @param int $int 输入的整数
     * @param bool $padUp 
     * @param string $entropy 额外的熵
     */
    public static function int2Alpha($in, $padUp = FALSE, $entropy = '') {
        $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        
        if ($entropy) {
            for ($n = 0; $n<strlen($index); $n++) {
                $i[] = substr( $index, $n ,1);
            }
         
            $passhash = hash('sha256', $entropy);
            $passhash = (strlen($passhash) < strlen($index))
                ? hash('sha512', $entropy)
                : $passhash;
         
            for ($n=0; $n < strlen($index); $n++) {
                $p[] =  substr($passhash, $n ,1);
            }
         
            array_multisort($p,  SORT_DESC, $i);
            $index = implode($i);
        }
     
        $base  = strlen($index);

        // Digital number  -->>  alphabet letter code
        if (is_numeric($padUp)) {
            $padUp--;
            if ($padUp > 0) {
                $in += pow($base, $padUp);
            }
        }
     
        $out = "";
        for ($t = floor(log($in, $base)); $t >= 0; $t--) {
            $bcp = bcpow($base, $t);
            $a   = floor($in / $bcp) % $base;
            $out = $out . substr($index, $a, 1);
            $in  = $in - ($a * $bcp);
        }
        $out = strrev($out); // reverse
     
      return $out;
    }
    
    /**
     * 将短字符串转为整数
     */
    public static function alpha2Int($in, $padUp = FALSE, $entropy = '') {
        $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if ($entropy) {
            for ($n = 0; $n<strlen($index); $n++) {
                $i[] = substr( $index, $n ,1);
            }
         
            $passhash = hash('sha256', $entropy);
            $passhash = (strlen($passhash) < strlen($index))
                ? hash('sha512', $entropy)
                : $passhash;
         
            for ($n=0; $n < strlen($index); $n++) {
                $p[] =  substr($passhash, $n ,1);
            }
         
            array_multisort($p,  SORT_DESC, $i);
            $index = implode($i);
        }
     
        $base  = strlen($index);
        
        // Digital number  <<--  alphabet letter code
        $in  = strrev($in);
        $out = 0;
        $len = strlen($in) - 1;
        for ($t = 0; $t <= $len; $t++) {
            $bcpow = bcpow($base, $len - $t);
            $out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
        }
     
        if (is_numeric($padUp)) {
            $padUp--;
            if ($padUp > 0) {
                $out -= pow($base, $padUp);
            }
        }
        $out = sprintf('%F', $out);
        $out = substr($out, 0, strpos($out, '.'));
        
        return $out;
    }
    
    /**
     * 加密字符串
     */
    static function encrypt($txtStream, $password = '', $lockstream = '') {
        $lockstream = $lockstream ?: 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        //随机找一个数字，并从密锁串中找到一个密锁值
        $lockLen = strlen($lockstream);
        $lockCount = rand(0, $lockLen - 1);
        $randomLock = $lockstream[$lockCount];
        //结合随机密锁值生成MD5后的密码
        $password = md5($password . $randomLock);
        //开始对字符串加密
        //$txtStream = base64_encode($txtStream);
        $tmpStream = '';
        $i=0; $j=0; $k = 0;
        for ($i=0; $i<strlen($txtStream); $i++) {
            $k = ($k == strlen($password)) ? 0 : $k;
            $j = (strpos($lockstream,$txtStream[$i]) + $lockCount + ord($password[$k])) % ($lockLen);
            $tmpStream .= $lockstream[$j];
            $k++;
        }
        
        return $tmpStream . $randomLock;
    }
    
    /**
     * 解密字符串
     */
    static function decrypt($txtStream, $password = '', $lockstream = '') {
        //密锁串，不能出现重复字符
        $lockstream = $lockstream ?: 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        
        $lockLen = strlen($lockstream);
        //获得字符串长度
        $txtLen = strlen($txtStream);
        //截取随机密锁值
        $randomLock = $txtStream[$txtLen - 1];
        //获得随机密码值的位置
        $lockCount = strpos($lockstream,$randomLock);
        //结合随机密锁值生成MD5后的密码
        $password = md5($password . $randomLock);
        //开始对字符串解密
        $txtStream = substr($txtStream , 0, $txtLen - 1);
        $tmpStream = '';
        $i=0; $j=0; $k = 0;
        for($i=0; $i<strlen($txtStream); $i++) {
            $k = ($k == strlen($password)) ? 0 : $k;
            $j = strpos($lockstream,$txtStream[$i]) - $lockCount - ord($password[$k]);
            while ($j < 0) {
                $j = $j + ($lockLen);
            }
            $tmpStream .= $lockstream[$j];
            $k++;
        }
        
        return $tmpStream;
    }
}
