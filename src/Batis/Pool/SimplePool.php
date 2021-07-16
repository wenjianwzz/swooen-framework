<?php
namespace Swooen\Batis\Pool;

use PDO;

class SimplePool implements PDOPool {

    protected $config;

    public function __construct(PDOConfig $config) {
        $this->config = $config;
    }

    public function has(): bool {
        return true;
    }

    public function create(): \PDO {
        return new \PDO($this->config->getDSN(), $this->config->getUser(), $this->config->getPassword(), [
            PDO::ATTR_CASE => PDO::CASE_NATURAL,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
    }

    public function get() {
        return $this->create();
    }

    public function returnback($pdo) {
    }
}
