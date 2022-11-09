<?php
namespace Swooen\Package\Route\Loader;

/**
 * 路由加载器
 * @author WZZ
 *        
 */
abstract class RouteLoader {

    /**
     * 加载路由
     * @return \Swooen\Package\Route\Route[]
     */
    abstract public function getRoutes();
}
