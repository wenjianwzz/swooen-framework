<?php
namespace Swooen\Server;

use Swooen\Application;
use Swooen\Package\Package;
use Swooen\Package\PackageHandleContext;
use Swooen\Package\PackageHandler;
use Swooen\Package\RawPackage;
use Swooen\Server\Writer\Writer;

/**
 * 包分发
 */
class PackageDispatcher {

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var PackageHandler[]
     */
    protected $handlers = [];

    public function __construct(Application $app) {
        $this->app = $app;
    }

    public function addHandler(PackageHandler ...$handlers) {
        foreach($handlers as $handler) {
            array_push($this->handlers, $handler);
        }
    }

    public function dispatch(Package $package, Writer $writer) {
        $context = $this->app->make(PackageHandleContext::class);
        $writer->send(new RawPackage('hello'));

    }
    
}