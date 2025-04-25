<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions\Http;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\Http\safe_int;

class SafeIntTest extends TestCase
{
    /**
     * @dataProvider castSafeIntDataProvider
     */
    #[DataProvider('castSafeIntDataProvider')]
    public function test_it_can_cast_safe_int(mixed $given, mixed $expected)
    {
        $this->assertSame($expected, safe_int($given));
    }

    public static function castSafeIntDataProvider()
    {
        yield [null, null];
        yield [1, 1];
        yield ['foo', 'foo'];
        yield [9_007_199, 9007199];
        yield [9007199254740990, 9007199254740990];
        yield ['9007199254740990', 9007199254740990];
        yield [-9007199254740990, -9007199254740990];
        yield ['-9007199254740990', -9007199254740990];
        yield [9007199254740991, '9007199254740991'];
        yield ['9007199254740991', '9007199254740991'];
        yield [-9007199254740991, '-9007199254740991'];
        yield ['-9007199254740991', '-9007199254740991'];
        yield [9007199254741001, '9007199254741001'];
        yield ['9007199254741001', '9007199254741001'];
        yield [-9007199254741001, '-9007199254741001'];
        yield ['-9007199254741001', '-9007199254741001'];

    }
}
