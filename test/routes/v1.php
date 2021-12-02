<?php

use Swooen\Communication\Route\Route;
use Swooen\Communication\Writer;

return [
    new Route('{path:.+}', function(Route $route, Writer $writer) {
        $writer->end('content');
    })
];