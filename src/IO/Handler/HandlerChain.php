<?php
namespace Swooen\IO\Handler;

use Swooen\Application;
use Swooen\IO\ConnectionFactory;

/**
 * 服务启动器，负责初始环境
 */
abstract class HandlerChain {

    protected $app;

    public function __construct(Application $application) {
        $this->app = $application;
    }

    /**
     * 设置连接工厂
     */
    public function withConnectionFactory(ConnectionFactory $connectionFactory): self {
        $this->app->instance(\Swooen\IO\ConnectionFactory::class, $connectionFactory);
		return $this;
	}
    
    /**
     * 设置处理器上下文钩子
     * @param $implement callable|class
     */
    public function withHandlerContextHook($implement): self {
        $this->app->bind(\Swooen\IO\Route\Hook\HandlerContextHook::class, $implement);
		return $this;
	}

    /**
     * 初始化路由
     * @param $implement callable|class
     */
    public function withRouteLoader($implement): self {
        $this->app->bind(\Swooen\IO\Route\Loader\RouteLoader::class, $implement);
		return $this;
    }

    /**
     * 启动服务
     */
    abstract public function boot(): void;
}