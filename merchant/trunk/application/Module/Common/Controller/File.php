<?php 

namespace Module\Common\Controller;

use Application\Controller\Front;

class File extends Front {
    
    public function __construct() {
        parent::__construct();
        $this->options = array(
            'AccessKeyId' => $this->config['Aliyun']['oss']['key'],
            'AccessKeySecret' => $this->config['Aliyun']['oss']['secret']
        );
    }

    /**
     * 上传图片
     */
    public function upload() {
        if (! $_FILES) {
            $this->response->json(array(
                'type' => 'error',
                'message' => '没有选择文件'
            ));
        }
        $file = $_FILES['Filedata'];
        // 获取扩展名
        $pathinfo = pathinfo($file['name']);
        $ext = $pathinfo['extension'];
        // 获取MIME类型
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $key = $this->uniqueFilename() . '.' . $ext;
        $path = $this->genDirname();
        $dir = ROOT . '/'. $path;
        if (! file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        $dest = $dir . $key;
        $data = array(
            'bucket' => '',
            'key'    => $path . $key,
            'name'   => $file['name'],
            'mime'   => $mime,
            'meta'   => array()
        );
        // 支持的图片
        if (preg_match('#(GIF|JPG|PNG|SWF|SWC|PSD|' .
            'TIFF|BMP|IFF|JP2|JPX|JB2|JPC|XBM|WBMP)#i', $ext)) {
            $size = getimagesize($file['tmp_name']);
            $data['meta'] = array(
                'width' => $size[0],
                'height' => $size[1]
            );
        }
        $modelFile = $this->model('Application:File');
        // 插入file
        $fileId = $modelFile->add($data);
        $data['src'] = $this->request->baseUrl() . '/' . $path . $key;
        if ($fileId && move_uploaded_file($file['tmp_name'], $dest)) {
            $data['fileId'] = $fileId;
            $this->response->json(array('content' => $data));
        } else {
            $this->response->json(array(
                'type' => 'error',
                'message' => '上传失败，请稍后再试'
            ));
        }
    }

    private function genDirname() {
        $hash = $this->com('System:Crypt\Hash');
        $pass = '@~degaosoft~#';
        return 'file/' . 
            $hash->int2Alpha(date('Ym'), false, $pass) . '/' . 
            $hash->int2Alpha(date('d'), false, $pass) . '/';
    }

    /**
     * 生成一个唯一的文件名
     */
    protected function uniqueFilename() {
        $hash = $this->com('System:Crypt\Hash');
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $pass = '@~degaosoft~#';
        return 
            $hash->int2Alpha(time(), false, $pass) .
            $hash->encrypt(uniqid(), $pass, $chars) .
            $hash->randomString(4, $chars);
    }
}