<?php
namespace Swooen\Communication\Route\Hook;

use Swooen\Communication\Route\Handler\HandlerContext;

/**
 * 处理器上下文钩子
 * 
 * @author WZZ
 *        
 */
class HandlerContextHook {

    /**
     * 当上下文被创建的时候调用，可以向上下文注入依赖
     */
    public function onCreate(HandlerContext $context) {

    }
}