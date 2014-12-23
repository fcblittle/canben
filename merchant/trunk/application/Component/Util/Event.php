<?php

namespace Application\Component\Util;

use System\Bootstrap;

class Event {
    
    private $model = null;
    
    public function __construct() {
        $this->model = Bootstrap::model('Application:Event');
    }
    
    public function trigger($event, $params = array()) {
        $triggers = $this->model->getTriggersByEvent($event);
        if (empty($triggers)) {
            return;
        }
        foreach ($triggers as $trigger) {
            Bootstrap::call($trigger->url, $params);
        }
    }
}
