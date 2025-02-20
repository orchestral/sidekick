<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions;

use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\transform_relative_path;

class TransformRelativePathTest extends TestCase
{
    public function test_it_can_resolve_relative_path()
    {
        $this->assertSame(__FILE__, transform_relative_path('./TransformRelativePathTest.php', __DIR__));
    }
}
