<?php

namespace Swooen\Data\Capsule;

use Swooen\Data\Connectors\ConnectionFactory;
use Swooen\Data\DatabaseManager;
use PDO;

class Manager {
    /**
     * The database manager instance.
     *
     * @var \Swooen\Data\DatabaseManager
     */
    protected $manager;
    
    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;
    
    /**
     * Create a new database capsule manager.
     * @return void
     */
    public function __construct(\Illuminate\Config\Repository $config) {
        $this->config = $config;
        $this->setupManager();
    }
    
    public function __destruct() {
        $this->manager->destroy();
    }

    /**
     * Build the database manager instance.
     *
     * @return void
     */
    protected function setupManager() {
        $factory = new ConnectionFactory();
        $this->manager = new DatabaseManager($factory, $this->config);
    }

    /**
     * Get a fluent query builder instance.
     *
     * @param  \Closure|\Swooen\Data\Query\Builder|string  $table
     * @param  string|null  $as
     * @param  string|null  $connection
     * @return \Swooen\Data\Query\Builder
     */
    public function table($table, $as = null, $connection = null) {
        return $this->getConnection($connection)->table($table, $as);
    }

    /**
     * Get a schema builder instance.
     *
     * @param  string|null  $connection
     * @return \Swooen\Data\Schema\Builder
     */
    public function schema($connection = null)
    {
        return $this->getConnection($connection)->getSchemaBuilder();
    }

    /**
     * Get a registered connection instance.
     *
     * @param  string|null  $name
     * @return \Swooen\Data\Connection
     */
    public function getConnection($name = null)
    {
        return $this->manager->connection($name);
    }

    /**
     * Register a connection with the manager.
     *
     * @param  array  $config
     * @param  string  $name
     * @return void
     */
    public function addConnection(array $config, $name = 'default') {
        $this->config->set("database.connections.{$name}", $config);
    }

    /**
     * Set the fetch mode for the database connections.
     *
     * @param  int  $fetchMode
     * @return $this
     */
    public function setFetchMode($fetchMode) {
        $this->config->set('database.fetch', $fetchMode);

        return $this;
    }

    /**
     * Get the database manager instance.
     *
     * @return \Swooen\Data\DatabaseManager
     */
    public function getDatabaseManager()
    {
        return $this->manager;
    }

}
