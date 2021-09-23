<?php
namespace Swooen\Redis\Pool;

interface RedisPool {

    /**
     * @return \Redis
     */
    public function get();

    /**
     * @param \Redis $redis
     */
    public function returnback($redis);
}
