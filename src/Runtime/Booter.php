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
     * 启动服务
     */
    abstract public function boot(): void;
}