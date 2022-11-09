<?php
namespace Swooen\Package\Route\Hook;

use Swooen\Package\Connection;
use Swooen\Package\Package\Package;
use Swooen\Package\Route\Handler\HandlerContext;
use Swooen\Package\Route\Route;

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
        return $package;
    }

    /**
     * 当处理器触发之后调用
     * 返回Package，该Package将会作为返回Package向下传递
     * 如果对Packge不做处理，应当原样返回
     * @return Package
     */
    public function after(HandlerContext $context, Route $route, Connection $connection, Package $returnPackage=null) {
        return $returnPackage;
    }
}