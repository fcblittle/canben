<?php 

/**
 * Accounts index controller.
 *
 * @copyright melofe.com
 * @package Accounts
 */

namespace Module\Account\Controller;

use Application\Controller\Front,
    Application\Model;

class Index extends Front {

    /**
     * @path home
     */
    public function _default() {
        $this->view->render('home');
    }
}