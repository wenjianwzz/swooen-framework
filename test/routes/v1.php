<?php

use Swooen\IO\RawPackage;
use Swooen\IO\Route\Route;
use Swooen\IO\Writer;

return [
    new Route('{path:.+}', function(Route $route, Writer $writer, $path) {
        return new RawPackage($path);
    })
];