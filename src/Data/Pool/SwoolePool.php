<?php
namespace Swooen\Data\Pool;

class SwoolePool implements PDOPool {

    protected $pool;

    public function __construct(PDOConfig $config) {
        $configObj = (new \Swoole\Database\PDOConfig())
                ->withHost($config->getHost())
                ->withPort($config->getPort())
                ->withDbName($config->getDb())
                ->withCharset($config->getCharset())
                ->withUsername($config->getUser())
                ->withPassword($config->getPassword());
        $this->pool = new \Swoole\Database\PDOPool($configObj, 16);
    }

    public function get() {
        return $this->pool->get();
    }

    public function returnback($pdo) {
        $this->pool->put($pdo);
    }
}
