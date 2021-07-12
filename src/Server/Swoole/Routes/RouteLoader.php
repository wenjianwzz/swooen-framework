<?php
namespace Swooen\Http\Routes;

/**
 * 路由加载器
 * @author WZZ
 *        
 */
abstract class RouteLoader {

    /**
     * 获取路由
     *
     * @return Route[]
     */
    abstract public function getRoutes();
}
