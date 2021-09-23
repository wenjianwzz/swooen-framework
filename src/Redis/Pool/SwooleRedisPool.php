<?php
namespace Swooen\Redis\Pool;

use Swoole\Coroutine\Channel;
use Throwable;

class SwooleRedisPool extends SimpleRedisPool {

    protected $conns;

    public function __construct(RedisConfig $config, $size) {
        parent::__construct($config);
        $this->conns = new Channel($size);
    }

    /**
     * @return \Redis
     */
    protected function getConn() {
        if ($this->conns->isEmpty()) {
            go(function() {
                $this->returnback($this->create());
            });
        }
        return $this->conns->pop(30);
    }

    public function get() {
        $try = $this->conns->capacity;
        do {
            $redis = $this->getConn();
            try {
                if ($redis->ping()) {
                    return $redis;
                }
            } catch (Throwable $t) {}
            -- $try;
        } while($try>0);
    }

    public function returnback($redis) {
        $this->conns->push($redis);
    }
}
