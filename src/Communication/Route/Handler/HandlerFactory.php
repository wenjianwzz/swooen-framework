<?php
namespace Swooen\Communication\Route\Handler;

use Illuminate\Support\Str;
use Swooen\Application;
use Swooen\Communication\Connection;
use Swooen\Communication\Package;
use Swooen\Communication\Route\Hook\HandlerContextHook;
use Swooen\Communication\Route\Route;
use Swooen\Communication\Route\Router;
use Swooen\Communication\Writer;

/**
 * 处理器工厂
 * 
 * @author WZZ
 *        
 */
class HandlerFactory {

    /**
     * @return HandlerContext
     */
    public function createContext(Application $app, Connection $connection, Route $route, Router $router, Package $package, Writer $writer) {
        $context = HandlerContext::create();
        $context->instance(Application::class, $app);
        $context->instance(\Swooen\Container\Container::class, $app);
        $context->instance(Connection::class, $connection);
        $context->instance(Route::class, $route);
        $context->instance(Router::class, $router);
        $context->instance(Package::class, $package);
        $context->instance(Writer::class, $writer);
        $context->instance(HandlerContext::class, $context);
        if ($app->has(HandlerContextHook::class)) {
            $app->make(HandlerContextHook::class)->onCreate($context);
        }
        return $context;
    }

    /**
     * @return callable
     */
    public function parse($action) {
        if (is_callable($action)) {
            return $action;
        } else if (is_string($action)) {
            if (!Str::contains($action, '@')) {
                $action .= '@__invoke';
            }
            return function(\Swooen\Container\Container $container, HandlerContext $handlerContext, Route $route) use ($action) {
                list($controller, $method) = explode('@', $action);
                $controller = $container->make($controller);
                $handlerContext->call([$controller, $method], $route->getParams());
            };
        }
    }
}
