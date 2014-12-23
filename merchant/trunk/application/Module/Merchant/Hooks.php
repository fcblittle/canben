<?php

namespace Module\Merchant;

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

}