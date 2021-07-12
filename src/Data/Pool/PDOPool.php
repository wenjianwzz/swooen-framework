<?php
namespace Swooen\Data\Pool;

interface PDOPool {

    /**
     * @return \PDO
     */
    public function get();

    public function returnback($pdo);
}
