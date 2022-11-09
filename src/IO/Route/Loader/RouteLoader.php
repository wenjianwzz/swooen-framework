<?php
namespace Swooen\IO\Route\Loader;

/**
 * 路由加载器
 * @author WZZ
 *        
 */
abstract class RouteLoader {

    /**
     * 加载路由
     * @return \Swooen\IO\Route\Route[]
     */
    abstract public function getRoutes();
}
