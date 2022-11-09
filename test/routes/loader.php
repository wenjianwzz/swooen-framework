<?php
return function(\Swooen\Application $app) {
    return new \Swooen\Package\Route\Loader\PHPFileLoader(
        $app->basePath('routes/v1.php'),
    );
};