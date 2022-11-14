<?php
namespace Swooen\Handle\CommonHanlers;

use Psr\Log\LoggerInterface;
use Swooen\Handle\HandleContext;
use Swooen\Handle\PackageHandler;
use Swooen\Package\Package;
use Swooen\Handle\Writer\Writer;

/**
 * 包处理器
 * @author WZZ
 */
class PackageLogger extends PackageHandler {

    protected $logger;

    public function __construct(?LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function handle(HandleContext $context, Package $package, Writer $nestedWriter, callable $next) {
        $this->logPackage('income package', $package);
        $writer = new PackageLoggerWriter($nestedWriter, $this);
        $context->instance(Writer::class, $writer);
        $next($context, $package, $writer);
        $context->instance(Writer::class, $nestedWriter);
    }

    public function logPackage($msg, Package $package) {
        if ($this->logger) {
            $this->logger->debug($msg, [print_r($package, true)]);
        }
    }

}
