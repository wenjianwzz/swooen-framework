<?php
namespace Swooen\Data\Pool;

interface PDOPool {

    public function has(): bool;

    public function get(): \PDO;

    public function returnback(\PDO $pdo);
}
