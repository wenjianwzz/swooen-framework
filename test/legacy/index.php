<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| First we need to get an application instance. This creates an instance
| of the application / container and bootstraps the application so it
| is ready to receive HTTP / Console requests from the environment.
|
*/

$app = require __DIR__.'/../bootstrap/runtime.php';
assert($app instanceof \Swooen\Runtime\RuntimeContext);
$app->bind(\Swooen\Communication\ConnectionFactory::class, \Swooen\Runtime\Http\GlobalToConnectionFactory::class);
$app->bind(\Swooen\Communication\Route\Loader\RouteLoader::class, function() {
    return new \Swooen\Communication\Route\Loader\PHPFileLoader(
        __DIR__.'/../routes/v1.php'
    );
});
$app->run();

