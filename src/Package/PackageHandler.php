<?php
namespace Swooen\Package;

use Swooen\Server\Writer\Writer;

/**
 * 包处理器
 * @author WZZ
 */
abstract class PackageHandler {

    protected $terminated = false;

    public abstract function handle(PackageHandleContext $context, Package $package, Writer $writer): Package;

    public function terminated() {
        return $this->terminated;
    }

    public function setTerminated($terminated): self {
        $this->terminated = $terminated;
        return $this;
    }

    public function reset(): self {
        $this->terminated = false;
        return $this;
    }
}
