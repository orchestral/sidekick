<?php

namespace Orchestra\Sidekick\Tests\Feature\Functions\Eloquent;

use Orchestra\Sidekick\Tests\Concerns\InteractsWithDatabase;
use PHPUnit\Framework\TestCase;

class ModelStateTest extends TestCase
{
    use InteractsWithDatabase;

    /** {@inheritDoc} */
    protected function createDatabaseSchema($schema): void
    {
        //
    }
}
