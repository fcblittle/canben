<?php

namespace Module\Home\Component\View;

use System\Bootstrap;
use System\Component\View\View;

class AppView extends View {
    
    protected $scripts = array();
    
    private static $misc = array();
    
    /**
     * 将模板嵌入frame
     * 
     * @param string $template 模板名
     * @
     */
    public function embed($template, $vars = array(), $theme = '') {
        $this->embedTemplate = $template;
        $this->embedVars = $vars;
        $this->embedTheme = $theme;
        $this->render('Module\Home:frame');
    }
    
    /**
     * 添加js脚本
     */
    public function js($scripts) {
        if (is_array($scripts)) {
            $this->scripts = array_merge($this->scripts, $scripts);
        } else {
            $this->scripts[] = $scripts;
        }
    }
    
    /**
     * 添加css脚本
     */
    public function css($styles) {
        if (is_array($styles)) {
            $this->styles = array_merge($this->styles, $styles);
        } else {
            $this->styles[] = $styles;
        }
    }
    
    /**
     * 格式化输出脚本
     * 
     * @param string $path 输入脚本路径
     * @return string 格式化的脚本路径
     */
    public function misc($path) {
        if (isset(self::$misc[$path])) {
            return self::$misc[$path];
        }
        $real = str_replace("\\", '/', $path);
        $real = preg_replace_callback(
            '#([^:]*:)#', 
            array($this, 'formatReplace'), 
            $real
        );
        $real = '/misc/' . str_replace("\\", '/', $real);
        //TODO: 添加版本
        //$real .= '?v=' . $version;
        self::$misc[$path] = $real;
        
        return self::$misc[$path];
    }

    /**
     * 格式化并替换命名空间
     */
    public function formatReplace($m) {
        // application
        if (preg_match('#^application#i', $m[1])) {
            return 'app/';
        }
        // 当前module
        if (substr($m[1], 0, 1) === ':') {
            $module = $this->thread->getModule();
        }
        // 任意module
        if (preg_match('#^module/([^:]*)#i', $m[1], $m2)) {
            $module = $m2[1];
        }
        return "module/" . strtolower($module) . "/";
    }

}
