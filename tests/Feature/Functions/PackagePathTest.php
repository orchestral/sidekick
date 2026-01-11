<?php

namespace Orchestra\Sidekick\Tests\Feature\Functions;

use Composer\InstalledVersions;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversFunction;

use function Orchestra\Sidekick\package_path;

#[CoversFunction('\Orchestra\Sidekick\package_path')]
class PackagePathTest extends TestCase
{
    public function test_it_can_resolve_base_path()
    {
        $this->assertSame(package_path(), realpath(InstalledVersions::getRootPackage()['install_path']));
    }
}
