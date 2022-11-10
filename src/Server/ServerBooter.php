<?php
namespace Swooen\Server;

use Swooen\Application;

/**
 * 服务启动器，负责初始环境
 */
abstract class ServerBooter {

    protected $context;

    protected $handlers;

    public function __construct(Application $context) {
        $this->context = $context;
    }
    
    /**
     * 启动服务
     */
    abstract public function boot(): void;
}