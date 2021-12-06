<?php

use Swooen\Communication\RawPackage;
use Swooen\Communication\Route\Route;
use Swooen\Communication\Writer;

return [
    new Route('{path:.+}', function(Route $route, Writer $writer, $path) {
        return new RawPackage($path);
    })
];