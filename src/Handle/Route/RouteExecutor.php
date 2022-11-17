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
class RouteExecutor extends PackageHandler {

    public function handle(HandleContext $context, Package $package, Writer $writer, callable $next) {
        if ($context->has(Route::class)) {
            $route = $context->get(Route::class);
            assert($route instanceof Route);
            $actions = array_map([$this, 'paserAction'], $route->getActions());
            foreach ($actions as $action) {
                $ret = $context->call($action, array_merge($route->getParams(), [
                    Package::class => $package
                ]));
                if ($ret && $ret instanceof Package) {
                    $package = $ret;
                }
            }
        }
        $next($context, $package, $writer);
    }

    protected function paserAction($action) {
        if (is_callable($action)) {
            return $action;
        } else if (is_string($action)) {
            if (!Str::contains($action, '@')) {
                $action .= '@__invoke';
            }
            return function(HandleContext $context, Route $route) use ($action) {
                list($controller, $method) = explode('@', $action);
                try {
                    $controller = $context->make($controller);
                } catch (\Throwable $t) {
                    throw new NotFoundHttpException('Unable to create Controller:'. $t->getMessage());
                }
                if (!is_callable([$controller, $method])) {
                    throw new NotFoundHttpException('Controller Not Callable'); 
                }
                return $context->call([$controller, $method], $route->getParams());
            };
        }
    }

}
