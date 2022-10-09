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

use Swooen\Runtime\Http\GenericBooter;

$context = require __DIR__.'/../bootstrap/context.php';
assert($context instanceof \Swooen\Runtime\RuntimeContext);
$booter = new GenericBooter($context);
$booter->boot();