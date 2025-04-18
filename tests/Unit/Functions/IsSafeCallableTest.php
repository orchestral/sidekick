<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions;

use Orchestra\Sidekick\FluentDecorator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\is_safe_callable;

class IsSafeCallableTest extends TestCase
{
    /**
     * @dataProvider callableDataProvider
     */
    #[DataProvider('callableDataProvider')]
    public function test_it_can_handle_is_safe_callable(mixed $given, bool $expected)
    {
        $this->assertSame($expected, is_safe_callable($given));
    }

    public static function callableDataProvider()
    {
        yield [fn () => true, true];
        yield ['trim', false];
        yield ['app', false];
        yield [[__CLASS__, 'callableDataProvider'], true];
        yield [[FluentDecorator::class, 'toArray'], false];
    }
}
