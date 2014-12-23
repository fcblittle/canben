<?php 

namespace Module\Account\Controller;

use System\Bootstrap;
use Application\Controller\Front;
    
class Logout extends Front {
    
    /**
     * @path accounts/logout
     */
    public function _default() {
        $this->auth->logout();
        $this->response->redirect(url('account/login'));
    }

}