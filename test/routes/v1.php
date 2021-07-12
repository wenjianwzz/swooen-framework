<?php

use Swooen\Communication\Route\Route;

return [
    new Route('{path:.+}', function(Route $route) {
        var_dump($route);
    })
];