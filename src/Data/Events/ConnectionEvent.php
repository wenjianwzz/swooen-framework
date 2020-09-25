<?php

namespace Swooen\Data\Events;

abstract class ConnectionEvent
{
    /**
     * The name of the connection.
     *
     * @var string
     */
    public $connectionName;

    /**
     * The database connection instance.
     *
     * @var \Swooen\Data\Connection
     */
    public $connection;

    /**
     * Create a new event instance.
     *
     * @param  \Swooen\Data\Connection  $connection
     * @return void
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->connectionName = $connection->getName();
    }
}
