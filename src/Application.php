<?php
namespace Swooen;

use Psr\Log\LoggerInterface;
use Swooen\Container\Container;
use Swooen\Exception\Handler;
use Swooen\Server\ServerBooter;

class Application extends Container {
    
    /**
     * The base path of the application installation.
     *
     * @var string
     */
    protected $basePath;

    public function __construct($basePath) {
        $this->basePath = $basePath;
        $this->instance(self::class, $this);
        $this->instance(parent::class, $this);
    }

    public function basePath($path = null) {
        return $this->basePath.($path ? '/'.$path : $path);
    }

    /**
     * 启动服务，开始监听并处理数据
     */
    public function boot(ServerBooter $booter) {
        $logger = $this->has(LoggerInterface::class)?$this->get(LoggerInterface::class):null;
        try {
            $booter->boot($this);
        } catch (\Throwable $t) {
            $errHandler = $this->make(Handler::class);
            $errHandler->report($t, $logger);
            throw $t;
        }
    }
}
