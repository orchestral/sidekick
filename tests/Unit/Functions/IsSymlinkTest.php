<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions;

use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\is_symlink;

class IsSymlinkTest extends TestCase
{
    public function test_it_return_false_when_path_is_not_a_symlink()
    {
        $this->assertFalse(is_symlink(__DIR__));
    }
}
