<?php
namespace Swooen\Redis\Pool;

interface RedisPool {

    /**
     * @return \Redis
     */
    public function get();

    /**
     * @return \Redis
     */
    public function proxy();

    /**
     * @param \Redis $redis
     */
    public function returnback($redis);
}
