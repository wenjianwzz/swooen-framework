<?php
namespace Swooen\Communication\Route;

use Swooen\Communication\Package;
use Swooen\Communication\Route\Loader\RouteLoader;
use Swooen\Communication\RouteablePackage;
use Swooen\Util\ReverseMake;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
                $r->addRoute(Router::METHOD, $route->getPath(), $route);
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
        if ($package instanceof RouteablePackage) {
            $found = $dispatcher->dispatch(static::METHOD, $package->getRoutePath());
        } else {
            $found = $dispatcher->dispatch(static::METHOD, static::ROUTE_PACKAGE_NOT_ROUTEABLE);
        }
        switch ($found[0]) {
            case \FastRoute\Dispatcher::FOUND:
                $route = clone $found[1];
                assert($route instanceof Route);
                $route->setParams($found[2]);
                return $route;
        }
        return false;
	}
}
