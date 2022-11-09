<?php
namespace Swooen\IO\Route\Handler;

use Swooen\Container\Container;

/**
 * 具体路由上下文
 * 
 * @author WZZ
 *        
 */
class HandlerContext extends Container {

    /**
     * @return static
     */
    public static function create() {
        return new static();
    }
}
