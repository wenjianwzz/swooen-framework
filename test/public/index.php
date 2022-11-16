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

use Swooen\Server\Generic\GenericBooter;

$app = require __DIR__.'/../bootstrap/app.php';
assert($app instanceof \Swooen\Application);
(new GenericBooter())->boot($app);