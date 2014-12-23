<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\View\Helper;

use System\Bootstrap;

/**
 * View helper: Block
 * 
 * @author xlight <i@im87.cn>
 */
class Block {
    
    /**
     * Returns content of a block.
     * 
     * Block::get('blog.latest')
     * 
     * @param string $path
     * @param string $action
     * @param array $params
     * @return string
     */
    public function get($path, $params = array()) {
        return Bootstrap::call($path, $params);
    }

}
