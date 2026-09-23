<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions;

use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\php_version_compare;

class PhpVersionCompareTest extends TestCase
{
    public function test_it_can_evaluate_php_version()
    {
        $this->assertSame(0, php_version_compare(PHP_VERSION));
        $this->assertTrue(php_version_compare(PHP_VERSION, '=='));
    }
}
