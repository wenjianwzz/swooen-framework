<?php
namespace Swooen\Handle\CommonHanlers;

use Swooen\Handle\HandleContext;
use Swooen\Handle\PackageHandler;
use Swooen\Package\Package;
use Swooen\Server\Writer\Writer;

/**
 * 包处理器
 * @author WZZ
 */
class PackageLogger extends PackageHandler {

    public function handle(HandleContext $context, Package $package, Writer $writer): Package {
        $writer->send($package);
        return $package;
    }

}
