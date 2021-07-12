<?php
namespace Swooen\Batis\Pool;

interface PDOPool {

    /**
     * @return \PDO
     */
    public function get();

    public function returnback($pdo);
}
