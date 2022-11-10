<?php


require_once __DIR__.'/../../vendor/autoload.php';

use Swooen\Handle\CommonHanlers\PackageLogger;
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
$app->instance(PackageDispatcher::class, $app->call(function(PackageDispatcher $dispatcher, PackageLogger $packageLogger) {
    $dispatcher->addHandler($packageLogger);
    return $dispatcher;
}));
return $app;
