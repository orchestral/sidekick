<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions;

use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\package_version_compare;
use function Orchestra\Sidekick\phpunit_normalize_version;

class PackageVersionCompareTest extends TestCase
{
    public function test_it_can_evaluate_package_version()
    {
        $this->assertTrue(package_version_compare('phpunit/phpunit', phpunit_normalize_version(), '='));
    }
}
