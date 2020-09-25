<?php

namespace Swooen\Data\Events;

use Illuminate\Contracts\Database\Events\MigrationEvent as MigrationEventContract;
use Swooen\Data\Migrations\Migration;

abstract class MigrationEvent implements MigrationEventContract
{
    /**
     * An migration instance.
     *
     * @var \Swooen\Data\Migrations\Migration
     */
    public $migration;

    /**
     * The migration method that was called.
     *
     * @var string
     */
    public $method;

    /**
     * Create a new event instance.
     *
     * @param  \Swooen\Data\Migrations\Migration  $migration
     * @param  string  $method
     * @return void
     */
    public function __construct(Migration $migration, $method)
    {
        $this->method = $method;
        $this->migration = $migration;
    }
}
