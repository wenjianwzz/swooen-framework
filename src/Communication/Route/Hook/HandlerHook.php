<?php
namespace Swooen\Communication\Route\Hook;

use Swooen\Communication\Connection;
use Swooen\Communication\Package;
use Swooen\Communication\Route\Handler\HandlerContext;
use Swooen\Communication\Route\Route;

/**
 * 处理器钩子
 * 
 * @author WZZ
 *        
 */
class HandlerHook {

    /**
     * 是否应该停止，停止意味着之后的钩子和处理器都不会触发。对此Package的处理结束
     */
    public function willStop() {
        return false;
    }

    /**
     * 当处理器触发之前调用
     * @
     */
    public function before(HandlerContext $context, Route $route, Package $package, Connection $connection) {
    }

    /**
     * 当处理器触发之后调用
     */
    public function after(HandlerContext $context, Route $route, Package $package, Connection $connection) {
    }
}