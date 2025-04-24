<?php

namespace Orchestra\Sidekick\Tests\Functions\Eloquent;

use Illuminate\Support\Fluent;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function Orchestra\Sidekick\Eloquent\normalize_value;
use stdClass;

class NormalizeValueTest extends TestCase
{
    public function test_it_can_resolve_a_stdclass_object()
    {
        $value = new stdClass;
        $value->framework = 'laravel';

        $this->assertSame('{"framework":"laravel"}', normalize_value($value));
    }

    /**
     * @dataProvider valuesDataProvider
     */
    #[DataProvider('valuesDataProvider')]
    public function test_it_can_hydrate_given_values(mixed $given, mixed $expected)
    {
        $this->assertSame($expected, normalize_value($given));
    }

    public static function valuesDataProvider()
    {
        yield ['laravel', 'laravel'];
        yield [123, 123];
        yield [['framework' => 'laravel'], '{"framework":"laravel"}'];
        yield [new Fluent(['framework' => 'laravel']), '{"framework":"laravel"}'];
        yield [collect([['framework' => 'laravel']]), '[{"framework":"laravel"}]'];
        yield [
            new class
            {
                public function __toString()
                {
                    return 'laravel nova';
                }
            },
            'laravel nova',
        ];
    }
}
