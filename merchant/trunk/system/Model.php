<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System;

/**
 * 模型（Model）基类
 * 
 * 所有模型（Model）必须扩展此基类才能正常使用
 * 
 * @author xlight <i@im87.cn>
 */
class Model {
    
    /**
     * Constructor, invoke hook.
     */
    public function __construct() {
        foreach (Bootstrap::getGlobal() as $key => $value) {
            $this->{$key} = $value;
        }
        Bootstrap::invokeHook('onModelInit', $this);
    }

}