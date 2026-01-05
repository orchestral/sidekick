<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions;

use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\Filesystem\join_paths;

class JoinPathsTest extends TestCase
{
    public function test_it_can_resolve_path()
    {
        $this->assertSame(
            realpath(__DIR__.'/JoinPathsTest.php'), join_paths(__DIR__, 'JoinPathsTest.php')
        );

        $this->assertSame(
            realpath(__DIR__.'/JoinPathsTest.php'), join_paths(__DIR__, '', 'JoinPathsTest.php')
        );
    }
}
