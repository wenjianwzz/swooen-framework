<?php
namespace Swooen\Handle;

use Swooen\Package\Package;
use Swooen\Handle\Writer\Writer;

/**
 * 包处理器
 * @author WZZ
 */
abstract class PackageHandler {

    protected $terminated = false;

    public abstract function handle(HandleContext $context, Package $package, Writer $writer): Package;

    public function terminated() {
        return $this->terminated;
    }

    public function setTerminated($terminated=true): self {
        $this->terminated = $terminated;
        return $this;
    }

    public function reset(): self {
        $this->terminated = false;
        return $this;
    }
}
