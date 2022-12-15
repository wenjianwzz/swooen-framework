<?php
namespace Swooen\Handle;

use Swooen\Application;
use Swooen\Container\Container;

/**
 * 连接上下文
 * @author WZZ
 */
class ConnectionContext extends Container {

    public function __construct(Application $app) {
        $this->instance(self::class, $this);
        $this->instance(parent::class, $this);
    }

}
