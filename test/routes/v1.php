<?php

use Swooen\Communication\BasicPackage;
use Swooen\Communication\Route\Route;

return [
    new Route('{path:.+}', function(Route $route) {
        return new BasicPackage([], []);
    })
];