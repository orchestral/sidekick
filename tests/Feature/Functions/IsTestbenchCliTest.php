<?php

namespace Orchestra\Sidekick\Tests\Feature\Functions;

use Orchestra\Testbench\Attributes\RequiresLaravel;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversFunction;

use function Orchestra\Sidekick\is_testbench_cli;
use function Orchestra\Testbench\remote;

#[WithConfig('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF')]
#[CoversFunction('Orchestra\Sidekick\working_path')]
class IsTestbenchCliTest extends TestCase
{
    #[RequiresLaravel('>=11.44.7')]
    public function test_it_can_detect_testbench_environment()
    {
        $this->assertFalse(is_testbench_cli());

        $this->assertTrue(transform(remote(fn () => is_testbench_cli()), function ($process) {
            $result = $process->mustRun();

            return $result->output();
        }));

        $this->assertTrue(transform(remote(fn () => is_testbench_cli(dusk: false)), function ($process) {
            $result = $process->mustRun();

            return $result->output();
        }));

        $this->assertFalse(transform(remote(fn () => is_testbench_cli(dusk: true)), function ($process) {
            $result = $process->mustRun();

            return $result->output();
        }));
    }
}
