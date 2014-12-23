<?php 
 
namespace Module\Common\Controller;

use Instance\Controller,
    System\Component\Http,
    Instance\Model;

class Js extends Controller\Front {
    
    public function __construct() {
        parent::__construct();
        $this->view->setOptions(array('contentType' => 'text/javascript'));
    }
    
    public function vars() {
        $this->view->render('common/js.vars');
    }
    
    public function tpl() {
        $list = explode(',', Http\Request::arg(3));
        
        foreach ($list as $item) {
            $tpl = $this->view->getContents('js/' . $item);
            // 去换行
            $tpl = str_replace(array("\n", "\r"), "", $tpl);
            // 去空格
            $tpl = preg_replace('#>(\s+)<#', '><', $tpl);
            $tpls[$item] = $tpl;
        }

        $this->view->tpls = $tpls;
        $this->view->render('common/js.tpl');
    }

}