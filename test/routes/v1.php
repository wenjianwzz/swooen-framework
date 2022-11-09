<?php

use Swooen\Package\RawPackage;
use Swooen\Package\Route\Route;
use Swooen\Package\Writer;

return [
    new Route('{path:.+}', function(Route $route, Writer $writer, $path) {
        return new RawPackage($path);
    })
];