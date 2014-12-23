<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\File;

class Uploader {
    
    const SEP = DIRECTORY_SEPARATOR;
    
    private $_files  = array();    // a local copy of $_FILES.
    private $_path   = '';         // absolute filepath.
    private $_rpath  = '';         // relative filepath.
    private $_params = array(
        'extensions' => array(),
        'dir'        => '',        // target file path.
        'maxSize'    => 0,         // max file size (KB).
        'rename'     => TRUE,      // rename filename or not.
        'prefix'     => '',        // filename prefix.
        'saveHandler' => NULL,     // How to save this file?
        'callback'   => NULL,      // callback function.
        'cleanName'  => TRUE       // Clean the file name for security.
    );
    private $_errors = array();
    
    /**
     * init.
     */
    public function __construct($params = array()) {
        $files = $_FILES;
        foreach ($files as $name => $file) {
            $files[$name]['tmp_name'] = str_replace(array("\\\\"), "\\", $file['tmp_name']);
        }
        $this->_files = $files;
        if ($params) $this->setParams($params);
    }
    
    /**
     * set params.
     * 
     * @param array $_params
     * @return object uploader.
     */
    public function setParams($params = array()) {
        if ($params['dir'] && ($params['dir'] !== $this->_params['dir'])) {
            $this->setPath($params['dir']);
        }
        $this->_params = array_merge($this->_params, $params);
        if (! $this->_params['maxSize']) $this->_params['maxSize'] = ini_get('upload_max_filesize');

        return $this;
    }
    
    /**
     * get uploaded file.
     * 
     * @param string $name filename.
     * @return array file info.
     */
    public function get($name) {
        return $this->_files[$name];
    }
    
    /**
     * set upload path.
     * 
     * @param string $_path
     * @return object uploader.
     */
    public function setPath($dir = '') {
        $dir = str_replace(array('/', '\\'), self::SEP, $dir);
        $this->_path = $dir ?: ROOT . '/files';
        if (! is_file($this->_path)) {
            $this->mkdirs($this->_path);
        }
        
        return $this;
    }
    
    /**
     * creat directory parent to child.
     * 
     * @param string $_path
     * @param number $mode 
     */
    public function mkdirs($path, $mode = 0777) {
        $dirs = explode(self::SEP, $path);
        for ($i = 0; $i < count($dirs); $i++) {
            $thispath = '';
            for ($n = 0; $n <= $i; $n++) {
                $thispath .= $dirs[$n] . self::SEP;
            }
            if (!file_exists($thispath)) {
                @mkdir($thispath, $mode);
            }
        } 
    }
    
    /**
     * move uploaded _files to target place.
     */
    public function upload() {
        foreach ($this->_files as $name => $file) {
            if ($this->_params['cleanName']) {
                $file['name'] = $this->clean($file['name']);
            }
            $pathinfo = pathinfo($file['name']);
            
            // extension
            $file['extension']  = $pathinfo['extension'];
            // filename
            $file['filename'] = $this->_params['rename'] ?
                File::makeUniqueName($this->_params['prefix']) :
                urlencode($pathinfo['filename']);
            // basename
            $file['basename'] = $file['filename'] . (
                $pathinfo['extension'] ? '.' . $pathinfo['extension'] : ''
            );
            // path
            $file['dir']   = $this->_params['dir'];
            // fully absolute path
            $file['target'] = $this->_path . self::SEP . $file['basename'];
            
            $file['errors'] = $this->validate($file);
            $this->_files[$name]  = $file;
            $this->_errors[$name] = $file['errors'];
            if (! $file['errors']) {
                if ($this->_params['saveHandler']) {
                    call_user_func($this->_params['saveHandler'], $file);
                } else {
                    move_uploaded_file($file['tmp_name'], $file['target']);
                }
            }
            if ($this->_params['callback']) {
                call_user_func($this->_params['callback'], $file);
            }
        }
        
        return $this;
    }
    
    public function validate($file) {
        $errors = array();
        if ($file['error']) {
            $errors[] = t($file['error']);
        } else {
            if (file_exists($file['target'])) {
                $errors[] = t('Target file(@name) exists.', array('@name' => $file['target']));
            }
            if ($file['size'] / 1024 / 1024 > $this->_params['maxSize']) {
                $errors[] = t('The file is too large.');
            }
            if ($this->_params['extensions'] && 
                ! in_array($file['extension'], $this->_params['extensions'])) {
                $errors[] = t('Extension is not allowed.');
            }
            if (! is_uploaded_file($file['tmp_name'])) {
                $errors[] = t('Illegal file.');
            }
        }
        
        return $errors;
    }
    
    /**
     * get all errors.
     */
    public function getErrors() {
        return $this->_errors;
    }
    
    /**
     * clean filename.
     */
    public function clean($filename) {
        $bad = array(
            "<!--",
            "-->",
            "'",
            "<",
            ">",
            '"',
            '&',
            '$',
            '=',
            ';',
            '?',
            '/',
            "%20",
            "%22",
            "%3c",      // <
            "%253c",    // <
            "%3e",      // >
            "%0e",      // >
            "%28",      // (
            "%29",      // )
            "%2528",    // (
            "%26",      // &
            "%24",      // $
            "%3f",      // ?
            "%3b",      // ;
            "%3d"       // =
        );
        
        return stripslashes(str_replace($bad, '', $filename));
    }
}

