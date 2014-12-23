<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Http;

use System\Bootstrap;

class Response {
    
    /**
     * HTTP status code.
     * @var int
     */
    private static $_code = 200;
    
    /**
     * Charset
     * @var string
     */
    private static $_charset = '';
    
    /**
     * Response options.
     */
    private static $_options = array(
        'version' => 1.1
    );
    
    /**
     * HTTP status text.
     * @var array
     */
    public static $statusTexts = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
    );
    
    /**
     * Set response options.
     */
    public function setOptions(array $options) {
        self::$_options = array_merge(self::$_options, $options);
    }
    
    /**
     * Redirect url.
     * 
     * @param string $url
     * @param array $options same as url()
     * @param number $time
     */
    public static function redirect($url, $options = array(), $time = 0) {
        if (! headers_sent()) {
            // redirect
            if ($time === 0) {
                header("Location: " . $url);
            } else {
                header("refresh:{$time};url={$url}");
            }
            exit();
        } else {
            $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
            exit();
        }
    }
    
    /**
     * Refresh page.
     */
    public static function refresh(array $options = array()) {
        $urlInfo = Request::urlInfo();
        if (! $options['query']) {
            $options['query'] = $urlInfo['query'];
        }
        self::redirect(url($urlInfo['path'], $options));
    }
    
    /**
     * Set HTTP status code.
     * 
     * @param int $code Status code
     */
    public static function setStatus($code) {
        self::$_code = $code;
    }
    
    /**
     * Get status code.
     * 
     * @return int
     */
    public static function getStatus() {
        return self::$_code;
    }
    
    /**
     * Send HTTP headers.
     */
    public static function sendHeader() {
        if (headers_sent()) {
            return;
        }
        header(sprintf('HTTP/%s %s %s', self::$_options['version'], self::$_code, self::$statusTexts[self::$_code]));
    }
    
    /**
     * Send a json message.
     * 
     * @param array $data 要输出的数据
     * @param bool $raw 是否输出源数据
     */
    public static function json($data = null, $raw = false) {
        if (! $raw) {
            $data = self::formatData($data);
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    /**
     * 格式化输出
     * 
     * @param mixed $data 待格式化数据
     * @param array 格式化的数据
     */
    public static function formatData($data = null) {
        if (! is_array($data)) {
            $data = array('content' => $data);
        }
        $data = array_merge(array(
            'type'    => 'status',
            'code'    => 200,
            'message' => ''
        ), $data);
        $data['message'] = $data['message'] ?: self::$statusTexts[$data['code']];
        return $data;
    }
    
    /**
     * Is status code invalid?
     * 
     * @return bool
     */
    public static function isInvalid() {
        return self::$_code < 100 || self::$_code >= 600;
    }
    
    /**
     * Is status code informational?
     * 
     * @return bool
     */
    public static function isInformational() {
        return self::$_code >= 100 && self::$_code < 200;
    }
    
    /**
     * Is status successful?
     * 
     * @return bool
     */
    public static function isSuccessful() {
        return self::$_code >= 200 && self::$_code < 300;
    }
    
    /**
     * Is redirection?
     * 
     * @return bool
     */
    public static function isRedirection() {
        return self::$_code >= 300 && self::$_code < 400;
    }
    
    /**
     * Is a client error?
     * 
     * @return bool
     */
    public static function isClientError()
    {
        return self::$_code >= 400 && self::$_code < 500;
    }
    
    /**
     * Is a server error?
     * 
     * @return bool
     */
    public static function isServerError() {
        return self::$_code >= 500 && self::$_code < 600;
    }
    
    /**
     * Is ok?
     * 
     * @return bool
     */
    public static function isOk() {
        return self::$_code === 200;
    }
    
    /**
     * Is forbidden?
     * 
     * @return bool
     */
    public function isForbidden() {
        return self::$_code === 403;
    }

    /**
     * Is not found?
     * 
     * @return bool
     */
    public function isNotFound() {
        return self::$_code === 404;
    }

    /**
     * Is redirect?
     * 
     * @return bool
     */
    public function isRedirect($location = NULL) {
        return in_array(self::$_code, array(201, 301, 302, 303, 307)) && (NULL === $location ?: $location == $this->headers->get('Location'));
    }

    /**
     * Is empty?
     * 
     * @return bool
     */
    public function isEmpty() {
        return in_array(self::$_code, array(201, 204, 304));
    }
}