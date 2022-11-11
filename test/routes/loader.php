<?php

use Swooen\Handle\Route\Loader\PHPFileLoader;

return function(\Swooen\Application $app) {
    return new PHPFileLoader(
        $app->basePath('routes/v1.php'),
    );
};