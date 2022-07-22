<?php
namespace Swooen\Communication\Route\Handler;

use Swooen\Util\Str;
use Swooen\Communication\Package;
use Swooen\Communication\Route\Route;

/**
 * 处理器工厂
 * 
 * @author WZZ
 *        
 */
class HandlerFactory {

    /**
     * 返回具体的处理函数
     * @return callable
     */
    public function parse(Route $route, Package $package) {
        $action = $route->getAction();
        if (is_callable($action)) {
            return $action;
        } else if (is_string($action)) {
            if (!Str::contains($action, '@')) {
                $action .= '@__invoke';
            }
            return function(\Swooen\Container\Container $container, HandlerContext $handlerContext, Route $route) use ($action) {
                list($controller, $method) = explode('@', $action);
                $controller = $container->make($controller);
                return $handlerContext->call([$controller, $method], $route->getParams());
            };
        }
    }
}
