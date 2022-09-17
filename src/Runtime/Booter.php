<?php
namespace Swooen\Runtime;

use Swooen\Communication\ConnectionFactory;
use Swooen\Container\Container;

/**
 * 服务启动器，负责初始环境
 */
abstract class Booter {

    protected $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    /**
     * 设置连接工厂
     */
    public function withConnectionFactory(ConnectionFactory $connectionFactory): self {
        $this->container->instance(\Swooen\Communication\ConnectionFactory::class, $connectionFactory);
		return $this;
	}
    
    /**
     * 设置处理器上下文钩子
     * @param $implement callable|class
     */
    public function withHandlerContextHook($implement): self {
        $this->container->bind(\Swooen\Communication\Route\Hook\HandlerContextHook::class, $implement);
		return $this;
	}

    /**
     * 初始化路由
     * @param $implement callable|class
     */
    public function withRouteLoader($implement): self {
        $this->container->bind(\Swooen\Communication\Route\Loader\RouteLoader::class, $implement);
		return $this;
    }

    /**
     * 启动服务
     */
    abstract public function boot(): void;
}