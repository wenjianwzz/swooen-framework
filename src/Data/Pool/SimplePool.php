<?php
namespace Swooen\Data\Pool;

class SimplePool implements PDOPool {

    protected $config;

    public function __construct(PDOConfig $config) {
        $this->config = $config;
    }

    public function has(): bool {
        return true;
    }

    public function get(): \PDO {
        return new \PDO($this->config->getDSN(), $this->config->getUser(), $this->config->getPassword(), [
            \PDO::ATTR_PERSISTENT => true
        ]);
    }

    public function returnback(\PDO $pdo) {
    }
}
