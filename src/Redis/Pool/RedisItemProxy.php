<?php
namespace Swooen\Redis\Pool;

class RedisItemProxy {

    protected $pool;

    protected $redis;

    public function __construct(RedisPool $pool) {
        $this->pool = $pool;
        $this->redis = $pool->get();
    }

    public function __call($name, $arguments) {
        return call_user_func_array([$this->redis, $name], $arguments);
    }

    public function __destruct() {
        $this->pool->returnback($this->redis);
    }

}
