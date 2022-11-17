<?php
require_once __DIR__.'/../../vendor/autoload.php';

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Swooen\Application;
use Swooen\Config\ConfigRepository;
use Swooen\Handle\CommonHanlers\ContextInitialize;
use Swooen\Handle\CommonHanlers\ExceptionHandler;
use Swooen\Handle\CommonHanlers\PackageLogger;
use Swooen\Handle\Route\Loader\RouteLoader;
use Swooen\Handle\Route\RouteExecutor;
use Swooen\Handle\Route\Router;
use Swooen\Provider\ConfigProvider;
use Swooen\Handle\PackageDispatcher;

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
$app = new \Swooen\Application('testapp', realpath(__DIR__.'/../'));

$app->provider(ConfigProvider::class);
$app->bind(RouteLoader::class, require $app->basePath('routes/loader.php'));

$app->bind(\Psr\Log\LoggerInterface::class, function(Application $app, ConfigRepository $config) {
    $logger = new Logger('sampleApp');
    $logger->pushHandler(new ErrorLogHandler());
    return $logger;
});

$app->instance(PackageDispatcher::class, $app->call(function(PackageDispatcher $dispatcher, 
            ExceptionHandler $exceptionHandler, Router $router, PackageLogger $packageLogger,
            ContextInitialize $contextInitialize, RouteExecutor $routeExecutor) {
    $dispatcher->addHandler($contextInitialize, $packageLogger, $exceptionHandler, $router, $routeExecutor);
    return $dispatcher;
}));
return $app;
