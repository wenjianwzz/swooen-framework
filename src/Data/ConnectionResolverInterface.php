<?php

namespace Swooen\Data;

interface ConnectionResolverInterface
{
    /**
     * Get a database connection instance.
     *
     * @param  string|null  $name
     * @return \Swooen\Data\ConnectionInterface
     */
    public function connection($name = null);

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection();

    /**
     * Set the default connection name.
     *
     * @param  string  $name
     * @return void
     */
    public function setDefaultConnection($name);
}
