<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions\Eloquent;

use App\Enums\TestBackedEnum;
use App\Enums\TestStringBackedEnum;
use Carbon\CarbonImmutable;
use Illuminate\Support\Fluent;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

use function Orchestra\Sidekick\Eloquent\normalize_value;

class NormalizeValueTest extends TestCase
{
    public function test_it_can_normalize_backed_enum()
    {
        $this->assertSame(1, normalize_value(TestBackedEnum::A));
        $this->assertSame(2, normalize_value(TestBackedEnum::B));
        $this->assertSame('A', normalize_value(TestStringBackedEnum::A));
        $this->assertSame('B', normalize_value(TestStringBackedEnum::B));
    }

    public function test_it_can_normalize_stdclass_object()
    {
        $value = new stdClass;
        $value->framework = 'laravel';

        $this->assertSame('{"framework":"laravel"}', normalize_value($value));
    }

    /**
     * @dataProvider valuesDataProvider
     */
    #[DataProvider('valuesDataProvider')]
    public function test_it_can_normalize_given_values(mixed $given, mixed $expected)
    {
        $this->assertSame($expected, normalize_value($given));
    }

    public static function valuesDataProvider()
    {
        $now = CarbonImmutable::now();

        yield ['laravel', 'laravel'];
        yield [123, 123];
        yield [['framework' => 'laravel'], '{"framework":"laravel"}'];
        yield [new Fluent(['framework' => 'laravel']), '{"framework":"laravel"}'];
        yield [collect([['framework' => 'laravel']]), '[{"framework":"laravel"}]'];
        yield [$now, $now->toJSON()];
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
