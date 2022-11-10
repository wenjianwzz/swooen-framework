<?php
namespace Swooen\Package;

/**
 * 包处理器
 * @author WZZ
 */
abstract class PackageHandler {

    public abstract function handle(PackageHandleContext $context, Package $package, PackageHandler $next);

}
