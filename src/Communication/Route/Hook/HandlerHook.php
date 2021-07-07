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
     * 当处理器触发之前调用
     * 返回Package，该Package将会作为输入Package向下传递
     * 如果对Packge不做处理，应当原样返回
     * 如果返回NULL，则表示不再继续往下执行
     * @return Package
     */
    public function before(HandlerContext $context, Route $route, Package $package, Connection $connection) {
    }

    /**
     * 当处理器触发之后调用
     * @return void
     */
    public function after(HandlerContext $context, Route $route, Package $package, Connection $connection) {
    }
}