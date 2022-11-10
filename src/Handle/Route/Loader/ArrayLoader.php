<?php
namespace Swooen\Handle\Route\Loader;

use Swooen\Handle\Route\Route;

/**
 * 路由加载器
 * @author WZZ
 *        
 */
class ArrayLoader extends RouteLoader {

    protected $routes;

    public function __construct(Route ...$routes) {
        $this->routes = $routes;
    }

    public static function strsToRoute() {
        
    }

    public function getRoutes() {
        return $this->routes;
    }
}
