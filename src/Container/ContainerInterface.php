<?php
namespace Swooen\Container;

interface ContainerInterface extends \Psr\Container\ContainerInterface {

    /**
     * 摧毁容器
     */
    public function destroy();

    /**
     * 注册实例
     */
    public function instance($abstract, $instance) : self;

    /**
     * 注册单例
     */
    public function singleton($abstract, $implement=null) : self;

    /**
     * 绑定实现，可以是类名/创建函数
     */
    public function bind($abstract, $implement) : self;

    /**
     * 别名
     */
    public function alias($abstract, $alias) : self;

    /**
     * 注册Providers
     */
    public function provider($provider) : self;

    public function unbind($abstract) : self;

    /**
     * 创建实例，并注入依赖
     */
    public function make($abstract, array $parameters=[]);

	/**
	 * 
	 * @param callable $call
	 * @param array $paramters 参数字典
	 * @return mixed
	 */
	public function call(callable $call, array $paramters=[]);
	
}
