<?php

namespace Module\Home;

use System\Bootstrap;

class Hooks {
    
    /**
     * Global config reference.
     * @var array
     */
    private $config = array();
    
    public function __construct() {
        $this->config = Bootstrap::getConfig();
    }
    
    /**
     * Implement hook: onControllerInit()
     */
    public function onControllerInit($controller) {
        $controller->view = Bootstrap::com(
            __NAMESPACE__ . ':View\AppView', 
            array($this->config['View']),
            true
        );
    }

}