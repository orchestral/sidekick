<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions;

use Composer\InstalledVersions;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\package_path;

#[CoversFunction('\Orchestra\Sidekick\package_path')]
class PackagePathTest extends TestCase
{
    public function test_it_can_resolve_base_path()
    {
        $this->assertSame(package_path(), realpath(InstalledVersions::getRootPackage()['install_path']));
    }
}
