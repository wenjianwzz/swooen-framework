<?php
namespace Swooen\Redis\Pool;

interface RedisPool {

    /**
     * @return \Redis
     */
    public function get();

    /**
     * @var \Redis
     */
    public function returnback($redis);
}
