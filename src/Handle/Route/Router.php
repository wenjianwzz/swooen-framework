<?php
namespace Swooen\Handle\Route;

use Swooen\Handle\HandleContext;
use Swooen\Package\Package;
use Swooen\Handle\PackageHandler;
use Swooen\Handle\Route\Loader\RouteLoader;
use Swooen\Handle\Writer\Writer;
use Swooen\Package\Features\Routeable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Wenjianwzz\Tool\Util\Str;

/**
 * 路由
 * 
 * @author WZZ
 *        
 */
class Router extends PackageHandler {

    /**
     * 恒定的HTTP方法
     */
    const METHOD = '-';

    const ROUTE_PACKAGE_NOT_ROUTEABLE = '<UnRoutable>';

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
                $r->addRoute('-', $path, $route);
			}
		}, [
            'routeParser' => \FastRoute\RouteParser\Std::class,
            'dataGenerator' => \FastRoute\DataGenerator\GroupCountBased::class,
            'dispatcher' => \FastRoute\Dispatcher\GroupCountBased::class,
            'routeCollector' => \FastRoute\RouteCollector::class,
        ]);
	}

    public function handle(HandleContext $context, Package $package, Writer $writer, callable $next) {
        $route = $this->dispatch($package);
        $context->instance(Route::class, $route);
        $next($context, $package, $writer);
    }

    /**
     * 分发Package, 返回匹配路由。返回的路由中，已经将参数注入。
     * @return Route
     */
	public function dispatch(Package $package) {
        $dispatcher = $this->createDispatcher();
        $routePath = static::ROUTE_PACKAGE_NOT_ROUTEABLE;
        if ($package instanceof Routeable) {
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
        throw new NotFoundHttpException('no route for '.$routePath);
	}
}
