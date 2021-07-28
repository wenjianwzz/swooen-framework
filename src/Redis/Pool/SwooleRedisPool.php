<?php
namespace Swooen\Redis\Pool;
use PDO;
class SwooleRedisPool implements RedisPool {

    protected $pool;

    public function __construct(RedisConfig $config, $size=4, $prefill=false) {
        $configObj = (new \Swoole\Database\RedisConfig())
                ->withHost($config->getHost())
                ->withPort($config->getPort())
                ->withDbIndex($config->getDbIndex())
                ->withReadTimeout($config->getReadTimeout())
                ->withAuth($config->getAuth())
                ->withRetryInterval(1)
                ->withTimeout($config->getTimeout());
        $this->pool = new \Swoole\Database\RedisPool($configObj, $size);
        if ($prefill) {
            $this->pool->fill();
        }
    }

    public function get() {
        return $this->pool->get();
    }

    public function returnback($redis) {
        $this->pool->put($redis);
    }
}
