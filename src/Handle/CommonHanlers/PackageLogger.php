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

    public function handle(HandleContext $context, Package $package, Writer $writer, callable $next) {
        if ($this->logger) {
            $this->logger->debug($package);
        }
        $next($context, $package, $writer);
    }

}
