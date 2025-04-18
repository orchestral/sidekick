<?php

namespace Orchestra\Sidekick\Tests\Php81\Functions;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\enum_name;

include_once 'Enums.php';

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
