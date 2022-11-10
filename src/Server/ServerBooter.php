<?php
namespace Swooen\Server;

use Swooen\Application;
use Swooen\Package\PackageHandler;

/**
 * 服务启动器，负责初始环境
 */
abstract class ServerBooter {

    protected $application;

    public function __construct(Application $application) {
        $this->application = $application;
    }
    
    /**
     * 启动服务
     */
    abstract public function boot(): void;
}