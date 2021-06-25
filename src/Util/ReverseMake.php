<?php
namespace Swooen\Util;

use Swooen\Container\Container;

trait ReverseMake {

    /**
     * 用容器创建自身实例
     * 只是为了方便IDE识别
     * @return static
     */
    public static function makeByContainer(Container $container, $args = []) {
        return $container->make(static::class, $args);
    }
}