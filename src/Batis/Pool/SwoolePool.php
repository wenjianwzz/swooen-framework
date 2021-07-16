<?php
namespace Swooen\Batis\Pool;
use PDO;
class SwoolePool implements PDOPool {

    protected $pool;

    public function __construct(PDOConfig $config, $size=4, $prefill=false) {
        $configObj = (new \Swoole\Database\PDOConfig())
                ->withHost($config->getHost())
                ->withPort($config->getPort())
                ->withDbName($config->getDb())
                ->withCharset($config->getCharset())
                ->withUsername($config->getUser())
                ->withPassword($config->getPassword())
                ->withOptions([
                    PDO::ATTR_CASE => PDO::CASE_NATURAL,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
                    PDO::ATTR_STRINGIFY_FETCHES => false,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
        $this->pool = new \Swoole\Database\PDOPool($configObj, $size);
        if ($prefill) {
            $this->pool->fill();
        }
    }

    public function get() {
        return $this->pool->get();
    }

    public function returnback($pdo) {
        $this->pool->put($pdo);
    }
}
