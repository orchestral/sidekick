<?php

use Illuminate\Foundation\Application;

use function Orchestra\Sidekick\laravel_version_compare;

it('can evaluate `laravel_version_compare()`', function () {
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
});
