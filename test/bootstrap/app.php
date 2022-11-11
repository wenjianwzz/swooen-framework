<?php


require_once __DIR__.'/../../vendor/autoload.php';

use Swooen\Application;
use Swooen\Handle\CommonHanlers\PackageLogger;
use Swooen\Handle\Route\Loader\RouteLoader;
use Swooen\Handle\Route\Router;
use Swooen\Server\PackageDispatcher;

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/
$app = new \Swooen\Application(realpath(__DIR__.'/../'));

$app->bind(RouteLoader::class, require $app->basePath('routes/loader.php'));
$app->instance(PackageDispatcher::class, $app->call(function(PackageDispatcher $dispatcher, Router $router) {
    $dispatcher->addHandler($router);
    return $dispatcher;
}));
return $app;
