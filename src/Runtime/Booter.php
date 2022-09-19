<?php
namespace Swooen\Runtime;

use Swooen\Communication\ConnectionFactory;
use Swooen\Container\Container;

/**
 * 服务启动器，负责初始环境
 */
abstract class Booter {

    protected $context;

    public function __construct(RuntimeContext $context) {
        $this->context = $context;
    }

    /**
     * 设置连接工厂
     */
    public function withConnectionFactory(ConnectionFactory $connectionFactory): self {
        $this->context->instance(\Swooen\Communication\ConnectionFactory::class, $connectionFactory);
		return $this;
	}
    
    /**
     * 启动服务
     */
    abstract public function boot(): void;
}