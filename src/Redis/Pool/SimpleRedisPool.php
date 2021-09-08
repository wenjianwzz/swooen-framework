<?php
namespace Swooen\Redis\Pool;

class SimpleRedisPool implements RedisPool {

    protected $config;

    public function __construct(RedisConfig $config) {
        $this->config = $config;
    }

    public function has(): bool {
        return true;
    }

    public function create(): \Redis {
        $redis = new \Redis();
        $host = $this->config->getHost();
        $port = $this->config->getPort();
        $auth = $this->config->getAuth();
        $db = $this->config->getDbIndex();
        $redis->connect($host, $port, $this->config->getTimeout(), NULL, 0, $this->config->getReadTimeout());
        if ($auth) {
            $redis->auth($auth);
        }
        if ($db) {
            $redis->select($db);
        }
        return $redis;
    }

    public function get() {
        return $this->create();
    }

    public function returnback($redis) {
        unset($redis);
    }
}
