<?php

/**
 * Configrations.
 * 
 * @copyright xlight http://www.im87.cn
 */

return array(
    // 公共配置(必须)
    'common' => array(
        // 是否调试模式
        'debug'             => true,
        // 默认模块
        //'defaultModule'     => 'Home',
        // 默认控制器
        'defaultController' => 'Index',
        // 默认操作
        'defaultAction'     => '_default',
        // 默认首页
        'frontPage'         => 'welcome',
        // 系统模板
        'tpl' => array(
            // 常规模式
            'default' => array(
                'error' => '',
                'exception' => '',
                '404' => 'Application\Resource\views\404'
            ),
            // 调试模式
            'debug' => array(
                'error' => '',
                'exception' => '',
            )
        ),
        // 是否开启url重写
        'urlRewrite'        => TRUE,
        // cookie domain
        'cookieDomain'      => '',
        // 程序加密salt
        'salt'              => '',
        // 已添加的模块
        'modules'           => array(
            'account', 
            'system', 
            'common', 
            'home',
            'official',
            'merchant',
            'customer',
            'fund'
        ),
        // 官方网站前缀
        'officialBaseUrl' => 'http://official.canben.dev/',
        // 登录API
        'loginApi' => 'http://official.canben.test/index.php/api/chklogin_formerchant'
        // 'loginApi' => 'http://eat.degaosoft.com/index.php/api/chklogin_formerchant'
    ),
    
    // System\Component\Http
    'Http' => array(
        'baseUrl'      => '',
        // Set this if the site is using a sub-domain as main domain.
        //'baseDomain' => 'sub.example.com'
    ),
    
    // System\Component\Db
    'Db' => array(
        'default' => array(
            'driver'   => 'mysql',
            'params'   => array(
                'hostname' => '192.168.1.4',
                'username' => 'root',
                'password' => 'degao_software',
                'database' => 'eat_app',
                'hostport' => '80',
                'prefix'   => 'biz_'
            )
        )
    ),
    
    // System\Component\View
    'View' => array(
        'extension'    => '.tpl.php'
    ),
    
    // System\Component\Cache
    'Cache' => array(
        'Db' => array(
            'params' => array(
                'table' => 'cache'
            )
        )
    ),
    
    // System\Component\Locale
    'Locale' => array(
        'queryString'     => 'lang',
        'defaultLanguage' => 'zh'
    ),
    
    // System\Component\Session
    'Session' => array(
        'table' => '{session}'
    ),
    
    'Mail' => array(
        'default' => array(
            'host' => '',
            'from' => '',
            'name' => '',
            'user' => '',
            'pass' => ''
        )
    ),
    
    // System\Component\Filter
    'Filter' => array(
        'allowedProtocols' => array(
            'ftp', 'http', 'https', 'irc', 'mailto', 'news', 
            'nntp', 'rtsp', 'sftp', 'ssh', 'tel', 'telnet', 'webcal'
        )
    ),

);
