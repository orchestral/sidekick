<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions;

use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\package_version_compare;
use function Orchestra\Sidekick\phpunit_normalize_version;

class PackageVersionCompareTest extends TestCase
{
    public function test_it_can_evaluate_package_version()
    {
        $this->assertTrue(package_version_compare('phpunit/phpunit', phpunit_normalize_version(), '='));
    }

    public function test_it_throws_exception_when_package_is_not_installed()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Package "orchestra/is-not-installed" is not installed');

        package_version_compare('orchestra/is-not-installed', '1.0.0', '=');
    }
}
