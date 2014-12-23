<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\File;

use System\Bootstrap;

class CloudFS {
    
    const SEP = DIRECTORY_SEPARATOR;
    
    public static $config = array();
    
    private static $servers = array();
    
    private $server = array();
    
    private $params = array();
    
    /**
     * init.
     */
    public function __construct($params = array()) {
        if (! self::$config) {
            $config = Bootstrap::getConfig();
            self::$config = $config['File'];
        }
        if ($params) {
            $this->params = array_merge($this->params, $params);
        }
        $this->server = self::$config['servers'][0];
    }
    
    public function getServer() {
        return $this->server;
    }
    
    /**
     * move uploaded _files to target place.
     */
    public function save(array $data) {
        $url = $this->server['host'] . self::$config['remoteHandler'];
        $resource = isset($data['tmp_name']) ? 
            '@' . $data['tmp_name'] :
            $data['resource'];
        // Curl post data
        $data = array(
            'action'     => 'save',
            'extension'  => $data['extension'],
            'filename'   => $data['filename'],
            'dir'        => $data['dir'],
            'resource'   => $resource
        );

        return $this->curlPost($url, $data);
    }
    
    public function delete($uri) {
        $url = $this->server['host'] . self::$config['remoteHandler'];
        $data = array(
            'action' => 'delete',
            'uri'    => $uri
        );
        
        return $this->curlPost($url, $data);
    }
    
    /**
     * Post data via curl.
     * 
     * @param string $url
     * @param array $data
     */
    private function curlPost($url, $data) {
        $data = array_merge(array(
            'user' => $this->server['user'],
            'pass' => $this->server['pass']
        ), $data);
        $ch = curl_init();
        // Handler.
        curl_setopt($ch, CURLOPT_URL, $url);
        // Return?
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // No header info.
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // Via form post
        curl_setopt($ch, CURLOPT_POST, 1);
        // Timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, self::$config['remoteTimeout']);
        // Post fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // Execute
        $return = json_decode(curl_exec($ch));
        // Close handler
        curl_close($ch);
        
        return $return;
    }
}

