<?php
namespace Swooen\Package;

use Swooen\Application;
use Swooen\Container\Container;

/**
 * 包处理上下文
 * @author WZZ
 */
class PackageHandleContext extends Container {

    public function __construct(Application $app) {
        $this->instance(Application::class, $app);
        $this->instance(get_class($app), $app);
        $this->instance(self::class, $this);
        $this->instance(parent::class, $this);
    }

}
