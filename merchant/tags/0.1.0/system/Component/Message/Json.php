<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Message;

class Json {
    
    /**
     * Send a json message.
     */
    public static function send($message, $type = 'status', $code = 0) {
        $message = array(
            'type'    => $type,
            'code'    => $code,
            'message' => $message
        );
        echo json_encode($message);
        exit();
    }

}
