<?php

namespace Swooen\Data;

use Doctrine\DBAL\Driver\PDOMySql\Driver as DoctrineDriver;
use Swooen\Data\Query\Grammars\MySqlGrammar as QueryGrammar;
use Swooen\Data\Query\Processors\MySqlProcessor;
use Swooen\Data\Schema\Grammars\MySqlGrammar as SchemaGrammar;
use Swooen\Data\Schema\MySqlBuilder;

class MySqlConnection extends Connection
{
    /**
     * Get the default query grammar instance.
     *
     * @return \Swooen\Data\Query\Grammars\MySqlGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return \Swooen\Data\Schema\MySqlBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new MySqlBuilder($this);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Swooen\Data\Schema\Grammars\MySqlGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar);
    }

    /**
     * Get the default post processor instance.
     *
     * @return \Swooen\Data\Query\Processors\MySqlProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new MySqlProcessor;
    }

    /**
     * Get the Doctrine DBAL driver.
     *
     * @return \Doctrine\DBAL\Driver\PDOMySql\Driver
     */
    protected function getDoctrineDriver()
    {
        return new DoctrineDriver;
    }
}
