<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions;

use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\laravel_version_compare;

class LaravelVersionCompareTest extends TestCase
{
    public function test_it_can_evaluate_laravel_version()
    {
        $laravel = transform(
            Application::VERSION,
            fn (string $version) => match ($version) {
                '13.x-dev' => '13.0.0',
                '12.x-dev' => '12.0.0',
                default => $version,
            }
        );

        $this->assertSame(0, laravel_version_compare($laravel));
        $this->assertTrue(laravel_version_compare($laravel, '=='));
    }
}
