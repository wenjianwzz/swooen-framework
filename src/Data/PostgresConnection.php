<?php

namespace Swooen\Data;

use Doctrine\DBAL\Driver\PDOPgSql\Driver as DoctrineDriver;
use Swooen\Data\Query\Grammars\PostgresGrammar as QueryGrammar;
use Swooen\Data\Query\Processors\PostgresProcessor;
use Swooen\Data\Schema\Grammars\PostgresGrammar as SchemaGrammar;
use Swooen\Data\Schema\PostgresBuilder;

class PostgresConnection extends Connection
{
    /**
     * Get the default query grammar instance.
     *
     * @return \Swooen\Data\Query\Grammars\PostgresGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return \Swooen\Data\Schema\PostgresBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new PostgresBuilder($this);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Swooen\Data\Schema\Grammars\PostgresGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar);
    }

    /**
     * Get the default post processor instance.
     *
     * @return \Swooen\Data\Query\Processors\PostgresProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new PostgresProcessor;
    }

    /**
     * Get the Doctrine DBAL driver.
     *
     * @return \Doctrine\DBAL\Driver\PDOPgSql\Driver
     */
    protected function getDoctrineDriver()
    {
        return new DoctrineDriver;
    }
}
