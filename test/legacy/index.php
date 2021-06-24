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
try {
    $app = require __DIR__.'/../bootstrap/app.php';
    assert($app instanceof \Swooen\Application);
    $factory = $app->make(\Swooen\Server\Legacy\GlobalToConnectionFactory::class);
    assert($factory instanceof \Swooen\Communication\ConnectionFactory);
    $conn = $factory->make();
    $package = $conn->next();
    var_dump($package->inputs());
} catch (\Throwable $t) {
    var_dump($t);
}

