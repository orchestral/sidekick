<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions;

use App\Enums\TestBackedEnum;
use App\Enums\TestEnum;
use App\Enums\TestStringBackedEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\enum_name;

class EnumNameTest extends TestCase
{
    /**
     * @dataProvider scalarDataProvider
     */
    #[DataProvider('scalarDataProvider')]
    public function test_it_can_handle_enum_name($given, $expected)
    {
        $this->assertSame($expected, enum_name($given));
    }

    public static function scalarDataProvider()
    {
        yield [TestEnum::A, 'A'];
        yield [TestBackedEnum::A, 'A'];
        yield [TestBackedEnum::B, 'B'];
        yield [TestStringBackedEnum::A, 'A'];
        yield [TestStringBackedEnum::B, 'B'];
    }
}
