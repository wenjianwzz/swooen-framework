<?php

use Swooen\Handle\Route\Route;
use Swooen\Handle\Writer\Writer;
use Swooen\Package\RawPackage;

return [
    Route::create('GET /test', [function(Route $route, Writer $writer) {
        $writer->end(new RawPackage($route->getPath()));
    }]),
    Route::create('GET /test2', ['TestController@test']),
    Route::create('test', [function(Route $route, Writer $writer) {
        $writer->end(new RawPackage($route->getPath()));
    }])
];