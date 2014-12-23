<?php

namespace Application\Controller;

use System\Bootstrap;

class AppApi extends Front {
    
    private $options = array(
        'export' => 'json',
        'raw'    => 0
    );

    private $permissions = array(
        'public' => array()
    );

    public function __construct($args = array()) {
        parent::__construct();

        $this->options = array_merge($this->options, $args);

        if (method_exists($this, 'init')) {
            $this->init();
        }

        // 权限声明
        if (method_exists($this, 'permission')) {
            $this->permissions = merge_options(
                $this->permissions,
                $this->permission()
            );
        }
        $action = $this->thread->getAction();
        if (! in_array($action, $this->permissions['public'])) {
            $this->auth($this->options['export'] === 'json');
        }
    }

    /**
     * 输出结果
     * 
     * @param mixed $data 结果集
     * @return mixed
     */
    protected function export($data = null, $options = array()) {
        if ($options) {
            $this->options = array_merge($this->options, $options);
        }
        switch ($this->options['export']) {
            case 'json':
                $this->response->json($data, $this->options['raw']);
                break;
            default:
                return $this->options['raw'] 
                    ? $data 
                    : $this->response->formatData($data);
        }
    }
    
}