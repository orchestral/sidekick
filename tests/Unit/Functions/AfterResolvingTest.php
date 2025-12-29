<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions;

use Orchestra\Sidekick\SensitiveValue;
use Orchestra\Testbench\Foundation\Application as Testbench;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\after_resolving;

class AfterResolvingTest extends TestCase
{
    public function test_it_define_after_resolving_action()
    {
        $tester = (object) [
            'expected' => null,
        ];

        $laravel = Testbench::create();

        after_resolving($laravel, 'sidekick.secret', function ($object) use (&$tester) {
            $this->assertInstanceOf(SensitiveValue::class, $object);

            $tester->expected = $object->getValue();
        });

        $laravel->bind('sidekick.secret', function () {
            return new SensitiveValue('laravel!');
        });

        $laravel->make('sidekick.secret');

        $this->assertSame('laravel!', $tester->expected);

        Testbench::flushState($this);
    }
}
