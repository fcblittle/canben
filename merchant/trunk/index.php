<?php

/**
 * 程序入口
 * 
 * @author xlight www.im87.cn
 */

 // 网站根目录
define('ROOT', __DIR__);

// 文件夹分隔符
define('SEP', DIRECTORY_SEPARATOR);

// 框架根目录
define('SYS_ROOT', ROOT . SEP . 'system');

// 程序根目录
define('APP_ROOT', ROOT . SEP . 'application');

// 模块根目录
define('MODULE_ROOT', APP_ROOT . SEP . 'Module');

// 引入引导文件
require SYS_ROOT . SEP . 'Bootstrap.php';

// 初始化并运行程序
$bootstrap = System\Bootstrap::getInstance();
$bootstrap->init()->runApplication();
