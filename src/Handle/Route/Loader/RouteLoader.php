<?php
namespace Swooen\Handle\Route\Loader;

/**
 * 路由加载器
 * @author WZZ
 *        
 */
abstract class RouteLoader {

    /**
     * 加载路由
     * @return \Swooen\Handle\Route\Route[]
     */
    abstract public function getRoutes();
}
