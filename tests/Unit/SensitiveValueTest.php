<?php

namespace Orchestra\Sidekick\Tests\Unit;

use Orchestra\Sidekick\SensitiveValue;
use PHPUnit\Framework\TestCase;

class SensitiveValueTest extends TestCase
{
    public function test_it_can_be_resolved()
    {
        $stub = new SensitiveValue('laravel');

        $this->assertSame('laravel', $stub->getValue());
        $this->assertSame([], $stub->__debugInfo());
        $this->assertSame('******', $stub->jsonSerialize());
        $this->assertSame('******', (string) $stub);
        $this->assertSame('"******"', json_encode($stub));
    }
}
