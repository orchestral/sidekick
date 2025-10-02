<?php

namespace Orchestra\Sidekick\Tests\Feature\Functions;

use Orchestra\Testbench\Attributes\RequiresLaravel;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversFunction;

use function Orchestra\Sidekick\working_path;
use function Orchestra\Testbench\package_path;
use function Orchestra\Testbench\remote;

#[WithConfig('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF')]
#[CoversFunction('Orchestra\Sidekick\working_path')]
class WorkingPathTest extends TestCase
{
    #[RequiresLaravel('>=11.44.7')]
    public function test_it_can_resolve_base_path()
    {
        $this->assertSame(base_path(), working_path());

        $this->assertSame(package_path(), transform(remote(fn () => working_path()), function ($process) {
            $result = $process->mustRun();

            return $result->output();
        }));
    }
}
