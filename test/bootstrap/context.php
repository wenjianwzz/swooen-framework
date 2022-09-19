<?php

require_once __DIR__.'/../../vendor/autoload.php';

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
$context = new \Swooen\Runtime\RuntimeContext(realpath(__DIR__.'/../'));
return $context;
