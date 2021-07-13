<?php
namespace Swooen\Batis\Pool;

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
        echo 'return '.spl_object_id($pdo).PHP_EOL;
        $this->pool->put($pdo);
    }
}
