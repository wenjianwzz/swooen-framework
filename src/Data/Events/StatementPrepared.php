<?php

namespace Swooen\Data\Events;

class StatementPrepared
{
    /**
     * The database connection instance.
     *
     * @var \Swooen\Data\Connection
     */
    public $connection;

    /**
     * The PDO statement.
     *
     * @var \PDOStatement
     */
    public $statement;

    /**
     * Create a new event instance.
     *
     * @param  \Swooen\Data\Connection  $connection
     * @param  \PDOStatement  $statement
     * @return void
     */
    public function __construct($connection, $statement)
    {
        $this->statement = $statement;
        $this->connection = $connection;
    }
}
