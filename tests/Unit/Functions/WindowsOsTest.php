<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions;

use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\windows_os;
use function windows_os as laravel_windows_os;

class WindowsOsTest extends TestCase
{
    public function test_it_return_false_when_path_is_not_a_symlink()
    {
        $this->assertSame(laravel_windows_os(), windows_os());
    }
}
