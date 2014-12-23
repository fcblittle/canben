<?php

use \System\Component\Crypt, \System\Loader;

/**
 * 生成完整图片或缩略图src
 * 
 * @param string $object 图片路径
 * @param string $thumb 缩略图规则
 * @return string 完整src
 */
function img($object, $thumb = '') {
    $src = preg_match('#http|https#', $object) ? $object : IMG_PREFIX . $object;
    if ($thumb) {
        $src = $src . '!' . $thumb;
    }

    return $src;
}

/**
 * 生成misc文件url
 */
function misc($name, $ver = 0) {
    static $scripts;

    $config = \System\Bootstrap::getConfig();
    
    $path = '';
    
    if (is_array($name)) {
        if (isset($name['b'])) {
            $arg[] = 'b=' . $name['b'];
        }
        if (isset($name['f'])) {
            $arg[] = 'f=' . implode(',', $name['f']);
            foreach ($name['f'] as $v) {
                $k = isset($name['b']) ? ($name['b'] . '/' . $v) : $v;
                $files[$k] = $scripts[$k];
            }
        }
        $name = 'misc/min/?' . implode('&', $arg);
        $ver = $ver ?: max($files);
    }
    
    $suffix = '';
    if (preg_match('#\.(css|js)$#', $name)) {
        $suffix = $ver === NULL ? '' : ($ver ?: $scripts[$name]);
        if ($suffix) {
            $suffix = (preg_match('#(\?)#', $name) ? '&' : '?') . $suffix;
        }
    }
    $path = '/' . $name . $suffix;
    
    return $path;
}

function js($name, $ver = NULL) {
    return misc($name, $ver);
}

function css($name, $ver = NULL) {
    return misc($name, $ver);
}

/**
 * xss过滤
 */
function xss($string) {
    return \System\Component\Filter\Filter::xss($string);
}

/**
 * 获取树形列表
 * 
 * @param array $data 菜单数据
 * @param string $parent 父菜单名
 * @return array 树形结构菜单
 */
function getTree($data, $parent = '') {
    $tree = array();
    foreach ($data as $k => $v) {
        foreach ($data as $k1 => $v1) {
            if ($v1->name === $v->parent) {
                $v1->children[] = $v;
            }
        }
    }
    foreach ($data as $k => $v) {
        if ($v->parent === $parent) {
            $tree[] = $v;
        } else {
            unset($data[$k]);
        }
    }
    return $tree;
}
    
/**
 * 人性化时间格式
 */
function prettyDate($timestamp, $default = 'Y-m-d H:i') {
    $diff = REQUEST_TIME - $timestamp;
    $result = '';
    if ($diff == 0 || $diff < 10) {
        $result = '刚刚';
    } elseif ($diff < 60) {
        $result = $diff . '秒钟前';
    } elseif ($diff < 3600) {
        $result = floor($diff / 60) . '分钟前';
    } elseif ($diff < 86400 && date('d', $timestamp) == date('d', REQUEST_TIME)) {
        $result = '今天 ' . date('H:i', $timestamp);
    } elseif ($diff < 172800 && date('d', $timestamp) + 1 == date('d', REQUEST_TIME)) {
        $result = '昨天 ' . date('H:i', $timestamp);
    } else {
        $result = date($default, $timestamp);
    }
    
    return $result;
}

/**
 * 
 * 截取字符串
 * @param string $str
 * @param int $len
 */
function utfSubstr($str,$len)
{
	$oldStr = $str;
	for($i=0;$i<$len;$i++)
	{
		$temp_str=substr($str,0,1);
		if(ord($temp_str) > 127)
		{
			$i++;
			if($i<$len)
			{
				$new_str[]=substr($str,0,3);
				$str=substr($str,3);
			}
		}
		else
		{
		$new_str[]=substr($str,0,1);
		$str=substr($str,1);
		}
	}
	$new_str = join($new_str);
	if (strlen($oldStr) > $len){
	   $new_str .= '..';
	}
	return $new_str;
}

/**
 * HTML转文本
 * @param html $html
 * @param html $allowStr
 */
function htmlToText($html,$allowStr = null){
   return str_replace("&nbsp;", "", strip_tags($html,$allowStr));
}

/**
 * 
 * 数组转换字符串
 * @param array $array
 */
function arrayToObject($array) {
    if(!is_array($array)) return $array;  
  
    $object = new stdClass();  
    if(is_array($array) && count($array) > 0)  
    {  
        foreach($array as $name=>$value)  
        {  
            $name = strtolower(trim($name));  
            if($name) $object->$name = arrayToObject($value);  
        }  
  
        return $object;
    }  
    else return FALSE;  
}  

function makeFilename($prefix = '', $pass = '~!m') {
    // 前缀
    $a = encrypt($prefix, $pass);
    // uniqid
    $b = uniqid();
    // 随机字符串
    $c = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 4);
    
    return $a . '-' . $b . '-' . $c;
}

function encrypt($txtStream, $password = '') {
    $lockstream = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
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

function decrypt($txtStream, $password = '') {
    //密锁串，不能出现重复字符
    $lockstream = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    
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
