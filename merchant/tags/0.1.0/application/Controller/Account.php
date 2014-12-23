<?php

namespace Application\Controller;

use System\Bootstrap;

class Account extends Front {

    public function __construct() {
        parent::__construct();

        if (! $this->auth->isLogin()) {
            $this->response->redirect(url('account/login'));
        }
    }

}