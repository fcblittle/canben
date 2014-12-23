<?php
 
/**
 * view.php
 * 
 * @author xlight <i@im87.cn>
 * @version 2.0
 */

namespace System\Component\View;

use System\Bootstrap,
    System\Exception,
    System\Loader,
    System\Component\Http;

class View {
    
    /**
     * Dedined variables.
     * @var array
     */
    private $variables   = array();
    
    /**
     * Registered helpers.
     * @var array
     */
    private static $helpers = array();
    
    /**
     * 活动线程
     * @var Thread
     */
    public $thread = null;
    
    /**
     * View options.
     * @var array
     */
    protected static $options = array(
        'theme'        => 'default',
        'charset'      => 'utf-8',
        'contentType'  => 'text/html',
        'extension'    => '.tpl.php'
    );
    
    /**
     * Constructor.
     * 
     * @param array $options
     */
    public function __construct($options = array()) {
        $this->thread = Bootstrap::getActiveThread();
        self::setOptions($options);
        $this->config = Bootstrap::getConfig();
        // 全局变量
        foreach (Bootstrap::getGlobal() as $key => $value) {
            $this->{$key} = $value;
        }
        // Invoke hook: onViewInit()
        Bootstrap::invokeHook('onViewInit', $this);
    }
    
    /**
     * 自动加载助手
     * 
     * @param string $name Helper name.
     * @return object Helper instance.
     */
    public function __call($name, $args) {
        return $this->getHelper($name, $args);
    }
    
    /**
     * 设置选项
     * 
     * @param array $options
     */
    public static function setOptions(array $options) {
        self::$options = array_merge(self::$options, $options);
    }
    
    /**
     * 定义变量
     * 
     * @param string|array $name
     * @param mixed $value
     */
    public function assign($name, $value = '') {
        if (is_array($name)) {
            $this->variables = array_merge($this->variables, $name);
        } elseif (is_object($name)){
            foreach($name as $key => $val)
                $this->variables[$key] = $val;
        } else {
            $this->variables[$name] = $value;
        }
    }
    
    /**
     * 渲染视图
     * 
     * @param string $file template file.
     * @param array $sets settings.
     * @param page output.
     */
    public function render($file = '', $theme = '', $sets = array()) {
        // Invoke hook: beforeViewRender()
        Bootstrap::invokeHook('beforeViewRender', $this);
        Http\Response::sendHeader();
        echo $this->fetch($file, $theme, $sets);
        // Invoke hook: onViewRender()
        Bootstrap::invokeHook('onViewRender', $this);
    }
    
    /**
     * 获取视图内容
     * 
     * @param string $name
     * @param array $sets
     * @return string Page content.
     */
    public function fetch($name = '', $theme = '', $sets = array()) {
        $tpl = $this->getTemplate($name, $theme);
        $contentType = isset($sets['contentType']) 
            ? $sets['contentType'] 
            : self::$options['contentType'];
        $charset = isset($sets['charset']) 
            ? $sets['charset'] 
            : self::$options['charset'];
        header("Content-Type:" . $contentType . "; charset=" . $charset);
        ob_start();
        ob_implicit_flush(0);
        extract($this->getVariables(), EXTR_REFS);
        include $tpl;

        return ob_get_clean();
    }
    
    /**
     * 获取视图文件
     * 
     * @param string $name
     * @param string $theme
     */
    public function getTemplate($name, $theme = '') {
        $theme = $theme ?: self::$options['theme'];
        $theme = $theme ? $theme . "\\" : $theme;
        $name = trim(str_replace('/', "\\", $name));
        $path = '';
        if (substr($name, 0, 1) === ':') {
            $module = $this->thread->getModule();
            $path .= "Module\\{$module}";
        }
        if (strstr($name, ':')) {
            $path .= str_replace(':', "\\Resource\\views\\{$theme}", $name);
        } else {
            $path = $name;
        }
        $file = Loader::find($path, self::$options['extension']);
        if ($file) {
            return $file;
        }
        throw new Exception(t('Cannot find template: @path', array('@path' => $path)));
    }

    /**
     * 获取变量
     * 
     * @param bool The value should pass by reference or not.
     * @return array Variables.
     */
    private function getVariables($ref = TRUE) {
        $props = array();
        $reflection = new \ReflectionObject($this);
        $rfProps = $reflection->getProperties(
            \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED
        );
        foreach ($rfProps as $prop) {
            $name = $prop->getName();
            if (! $prop->isStatic()) {
                $ref ?
                    $props[$name] = & $this->{$name} : 
                    $props[$name] = $this->{$name};
            }
        }

        return array_merge($this->variables, $props);
    }
    
    /**
     * 引入视图区域
     * 
     * @param string $name
     * @param string $theme
     */
    public function region($name, $variables = array(), $theme = '') {
        // 防止命名冲突
        $_TPL_ = $name; 
        extract($this->getVariables(), EXTR_REFS);
        // Add local variables
        if (! empty($variables)) {
            extract($variables, EXTR_REFS);
        }
        include $this->getTemplate($_TPL_, $theme);
    }
    
    /**
     * 获取模板内容
     */
    public function getContents($tpl, $theme = '') {
        return file_get_contents($this->getTemplate($tpl, $theme));
    }
    
    /**
     * 注册助手
     * 
     * @param string $name
     * @param string $path
     */
    public static function registerHelper($name, $path) {
        self::$helpers[$name] = $path;
    }
    
    /**
     * 获取助手
     * 
     * @param string $name
     * @param array $params
     * @return object helper instance
     */
    public function getHelper($name, $params = array()) {
        $path = isset(self::$helpers[$name])
            ? self::$helpers[$name]
            : __NAMESPACE__ . '\\Helper\\' . ucfirst($name);
        $path = Bootstrap::formatPath($path);
        if ($file = Loader::load($path)) {
            $reflection = new \ReflectionClass($path);
            return $reflection->newInstanceArgs($params);
        } else {
            throw new Exception(t('Cannot find view helper: @name', array('@name' => $name)));
        }
    }
}
