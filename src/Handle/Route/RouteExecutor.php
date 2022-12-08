<?php
namespace Swooen\Handle\Route;

use Swooen\Handle\HandleContext;
use Swooen\Package\Package;
use Swooen\Handle\PackageHandler;
use Swooen\Handle\Writer\Writer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Wenjianwzz\Tool\Util\Str;

/**
 * 路由执行器
 * 
 * @author WZZ
 *        
 */
class RouteExecutor extends PackageHandler {

    public function handle(HandleContext $context, Package $oriPackage, Writer $writer, callable $next) {
        $package = $oriPackage;
        if ($context->has(Route::class)) {
            $route = $context->get(Route::class);
            assert($route instanceof Route);
            $actions = $route->getActions();
            foreach ($actions as $actionDef) {
                $action = $this->paserAction($actionDef);
                $ret = $context->call($action, $route->getParams());
                if ($ret && $ret instanceof Package) {
                    $package = $ret;
                    // 容器中替换成结果，在Action的执行周期内，Package统一
                    $context->instance(Package::class, $package);
                }
            }
        } else {
            throw new ServiceUnavailableHttpException();
        }
        // 回归
        $context->instance(Package::class, $oriPackage);
        $next($context, $package, $writer);
    }

    protected function paserAction($action) {
        return function(HandleContext $context, Route $route, Package $package) use ($action) {
            $call = null;
            if (is_callable($action)) {
                $call = $action;
            } else if (is_string($action)) {
                if (!Str::contains($action, '@')) {
                    $action .= '@__invoke';
                }
                list($actionClass, $method) = explode('@', $action);
                try {
                    $actionObj = $context->make($actionClass);
                } catch (\Throwable $t) {
                    throw new NotFoundHttpException('Unable to create Action: '. $t->getMessage());
                }
                $call = [$actionObj, $method];
                if (!is_callable([$actionObj, $method])) {
                    throw new NotFoundHttpException('Action Not Callable'); 
                }
            }
            return $context->call($call, $route->getParams());
        };
        
    }

}
