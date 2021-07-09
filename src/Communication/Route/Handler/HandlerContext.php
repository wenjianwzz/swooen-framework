<?php
namespace Swooen\Communication\Route\Handler;

use Swooen\Container\Container;

/**
 * 路由帮助
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
