<?php
namespace Swooen\IO\Route;

use Swooen\Package\Package;
use Swooen\IO\Route\Exception\NotFoundException;
use Swooen\IO\Route\Loader\RouteLoader;
use Swooen\IO\RouteablePackage;
use Wenjianwzz\Tool\Util\ReverseMake;

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
     * @var RouteLoader
     */
    protected $loader;

    public function __construct(RouteLoader $loader) {
        $this->loader = $loader;
    }

    /**
     * @return \FastRoute\Dispatcher
     */
	public function createDispatcher() {
        return \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
            $routes = $this->loader->getRoutes();
            foreach ($routes as $route) {
                $path = $route->getPath();
                $r->addRoute(Router::METHOD, $path, $route);
			}
		}, [
            'routeParser' => \FastRoute\RouteParser\Std::class,
            'dataGenerator' => \FastRoute\DataGenerator\GroupCountBased::class,
            'dispatcher' => \FastRoute\Dispatcher\GroupCountBased::class,
            'routeCollector' => \FastRoute\RouteCollector::class,
        ]);
	}
    
    /**
     * 分发Package, 返回匹配路由。返回的路由中，已经将参数注入。
     * @return Route
     */
	public function dispatch(Package $package) {
        $dispatcher = $this->createDispatcher();
        $routePath = static::ROUTE_PACKAGE_NOT_ROUTEABLE;
        if ($package instanceof RouteablePackage) {
            $routePath = $package->getRoutePath();
        }
        $found = $dispatcher->dispatch(static::METHOD, $routePath );
        switch ($found[0]) {
            case \FastRoute\Dispatcher::FOUND:
                $route = clone $found[1];
                assert($route instanceof Route);
                $route->setParams($found[2]);
                return $route;
        }
        throw new NotFoundException('no route for '.$routePath);
	}
}
