<?php
namespace Swooen\Communication\Route;

use Swooen\Communication\Package;
use Swooen\Communication\RouteablePackage;
use Swooen\Util\ReverseMake;

/**
 * 路由
 * 
 * @author WZZ
 *        
 */
class Router {

    use ReverseMake;

    /**
     * 恒定的HTTP方法
     */
    const METHOD = 'INTERNAL';

    const ROUTE_PACKAGE_NOT_ROUTEABLE = 'NOT_ROUTEABLE';

    /**
     * All of the routes waiting to be registered.
     *
     * @var Route[]
     */
    protected $routes = [];

	public function dispatcher() {
        return \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
            foreach ($this->routes as $route) {
                $r->addRoute($route->getMethod(), $route->getUri(), $route);
			}
		});
	}
    
    /**
     * 分发Package, 返回匹配路由
     * @return Route
     */
	public function dispatch(Package $package) {
        $dispatcher = $this->dispatcher();
        if ($package instanceof RouteablePackage) {
            return $dispatcher->dispatch(static::METHOD, $package->getRoutePath());
        } else {
            return $dispatcher->dispatch(static::METHOD, static::ROUTE_PACKAGE_NOT_ROUTEABLE);
        }
	}

    protected function corsRoutes() {
        $routes = [];
        foreach ($this->routes as $route) {
            $uri = $route->getUri();
            $methods = $route->getMethod();
            $headers = $route->getCorsHeaders();
            is_array($methods) or $methods = [$methods];
            if (isset($routes[$uri])) {
                // 之前存在相同uri，合并
                $methods = array_unique(array_merge($routes[$uri][0], $methods));
                $headers = array_unique(array_merge($routes[$uri][2], $headers));
            }
            $routes[$uri] = [$methods, $uri, $headers];
        }
    }

    /**
     * Add a route to the collection.
     *
     * @param  Route  $route
     * @return void
     */
    public function addRoute(Route $route) {
        $methods = $route->getMethod();
        is_array($methods) or $methods = [$methods];
        $uri = $route->getUri();
        foreach ($methods as $verb) {
            $this->routes[$verb.$uri] = $route;
        }
        if ($route->isCorsEnable()) {
            $verb = 'OPTIONS';
            $headers = $route->getCorsHeaders();
            if (isset($this->routes[$verb.$uri])) {
                $corsRoute = $this->routes[$verb.$uri];
                if ($corsRoute instanceof CorsRoute) {
                    // 之前存在相同uri，合并
                    $corsRoute->setCorsHeaders($headers);
                    $corsRoute->setCorsMethod($methods);
                }
            } else {
                $this->routes[$verb.$uri] = (new CorsRoute($verb, $uri, function(Route $route, \Swooen\Http\Writer\Writer $writer) {
                    if ($route instanceof CorsRoute) {
                        $writer->header('Access-Control-Allow-Methods', join(', ', $route->getCorsMethod()));
                        $writer->header('Access-Control-Max-Age', $this->corsMaxAge);
                        $writer->header('Access-Control-Allow-Headers', join(', ', $route->getCorsHeaders()));
                    }
                    $writer->end();
                }, [], []))->setCorsHeaders($headers)->setCorsMethod($methods);
            }
        }
    }

    /**
     * Get the raw routes for the application.
     *
     * @return Route[]
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * Get the value of corsHosts
     */ 
    public function getCorsHosts() {
        return $this->corsHosts;
    }

    /**
     * Set the value of corsHosts
     *
     * @return  self
     */ 
    public function setCorsHosts($corsHosts) {
        $this->corsHosts = $corsHosts;
        return $this;
    }
}
