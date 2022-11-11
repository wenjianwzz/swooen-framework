<?php

use Swooen\Handle\Route\Route;
use Swooen\Handle\Writer\Writer;
use Swooen\Package\RawPackage;

return [
    Route::create('/test', function(Route $route, Writer $writer, $path) {
        $writer->send(new RawPackage($path));
    })
];