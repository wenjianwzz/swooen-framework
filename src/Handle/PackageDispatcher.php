<?php
namespace Swooen\Handle;

use Swooen\Application;
use Swooen\Package\Package;
use Swooen\Handle\HandleContext;
use Swooen\Handle\PackageHandler;
use Swooen\Handle\Writer\Writer;

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
    
    public function dispatch(HandleContext $context, Package $package, Writer $writer) {
        $context->instance(Package::class, $package);
        $context->instance(Writer::class, $writer);
        $pos = count($this->handlers) - 1;
        $next = function() {};
        while ($pos >= 0) {
            $handler = $this->handlers[$pos];
            --$pos;
            $next = function(HandleContext $context, Package $package, Writer $writer) use ($handler, $next) {
                $handler->handle($context, $package, $writer, $next);
            };
        }
        $next($context, $package, $writer);
    }
    
}