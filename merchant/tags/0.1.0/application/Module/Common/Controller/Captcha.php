<?php 

namespace Module\Common\Controller;

class Captcha extends \System\Controller {
        
    public function verify() {
        $this->response->json(array(
            'content' => (int) ($_GET['captcha'] === $_SESSION['captcha'])
        ));
    }
    
    // cool-php-captcha
    public function a() {
        $cap = $this->com('System:Captcha\CoolPHPCaptcha\SimpleCaptcha');
        $cap->wordsFile = NULL;
        $cap->maxRotation = 5;
        $cap->minWordLength = 4;
        $cap->maxWordLength = 4;
        $cap->backgroundColor = array(243, 243, 243);
        $cap->width = 100;
        $cap->height = 31;
        $cap->Yperiod = 30;
        $cap->Yamplitude = 1;
        $cap->Xperiod = 30;
        $cap->Xamplitude = 1;
        $cap->colors = array(
            array(22, 163, 35), // green
            array(214, 36, 7),  // red
        );
        $cap->scale = 3;
        $cap->CreateImage();
    }
}