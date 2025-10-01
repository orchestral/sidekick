<?php

namespace Orchestra\Sidekick\Tests\Feature\Functions;

use Orchestra\Testbench\Attributes\RequiresLaravel;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\TestCase;

use function Orchestra\Sidekick\is_testbench_cli;
use function Orchestra\Testbench\remote;

#[WithConfig('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF')]
class IsTestbenchCliTest extends TestCase
{
    #[RequiresLaravel('>=11.44.7')]
    public function test_it_can_detect_testbench_environment()
    {
        $process = remote(fn () => is_testbench_cli());
        $result = $process->mustRun();

        $this->assertFalse(is_testbench_cli());
        $this->assertTrue($result->output());
    }
}
