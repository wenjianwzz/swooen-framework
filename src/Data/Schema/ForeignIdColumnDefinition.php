<?php

namespace Swooen\Data\Schema;

use Illuminate\Support\Str;

class ForeignIdColumnDefinition extends ColumnDefinition
{
    /**
     * The schema builder blueprint instance.
     *
     * @var \Swooen\Data\Schema\Blueprint
     */
    protected $blueprint;

    /**
     * Create a new foreign ID column definition.
     *
     * @param  \Swooen\Data\Schema\Blueprint  $blueprint
     * @param  array  $attributes
     * @return void
     */
    public function __construct(Blueprint $blueprint, $attributes = [])
    {
        parent::__construct($attributes);

        $this->blueprint = $blueprint;
    }

    /**
     * Create a foreign key constraint on this column referencing the "id" column of the conventionally related table.
     *
     * @param  string|null  $table
     * @return \Illuminate\Support\Fluent|\Swooen\Data\Schema\ForeignKeyDefinition
     */
    public function constrained($table = null)
    {
        return $this->references('id')->on($table ?: Str::plural(Str::before($this->name, '_id')));
    }

    /**
     * Specify which column this foreign ID references on another table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent|\Swooen\Data\Schema\ForeignKeyDefinition
     */
    public function references($column)
    {
        return $this->blueprint->foreign($this->name)->references($column);
    }
}
