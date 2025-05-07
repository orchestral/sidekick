<?php

namespace Orchestra\Sidekick\Tests\Concerns;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;

trait InteractsWithDatabase
{
    /**
     * Setup the test environment.
     */
    protected function setUpTestEnvironmentForDatabase(): void
    {
        $db = new DB;

        $db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        $db->bootEloquent();
        $db->setAsGlobal();

        $this->createDatabaseSchema(
            Model::getConnectionResolver()->connection()->getSchemaBuilder()
        );
    }

    /**
     * Create database schema for the test environment.
     *
     * @param  \Illuminate\Database\Schema\Builder  $schema
     */
    protected function createDatabaseSchema($schema): void
    {
        //
    }
}
