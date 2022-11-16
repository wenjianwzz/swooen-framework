<?php

use Swooen\Handle\Route\Route;
use Swooen\Handle\Writer\Writer;
use Swooen\Package\RawPackage;

return [
    Route::create('GET /test', function(Route $route, Writer $writer) {
        $writer->send(new RawPackage($route->getPath()));
    }),
    Route::create('test', function(Route $route, Writer $writer) {
        $writer->send(new RawPackage($route->getPath()));
    })
];