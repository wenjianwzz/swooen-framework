<?php
namespace Swooen\Package;

use Swooen\Application;
use Swooen\Container\Container;

/**
 * 包处理器
 * @author WZZ
 */
abstract class PackageHandler {

    /**
     * @var Container
     */
    protected $context;

    /**
     * @var Application
     */
    protected $app;

    public function setup(Application $app, Container $context) {
        $this->app = $app;
        $this->context = $context;
    }

    public abstract function handle(Package $package, PackageHandler $next);

}
