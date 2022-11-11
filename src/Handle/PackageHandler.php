<?php
namespace Swooen\Handle;

use Swooen\Package\Package;
use Swooen\Handle\Writer\Writer;

/**
 * 包处理器
 * @author WZZ
 */
abstract class PackageHandler {

    public abstract function handle(HandleContext $context, Package $package, Writer $writer, callable $next);

}
