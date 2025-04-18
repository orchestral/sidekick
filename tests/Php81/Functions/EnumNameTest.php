<?php

namespace Orchestra\Sidekick\Tests\Php81\Functions;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhp;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\enum_name;

if (PHP_VERSION_ID >= 80100) {
    include_once 'Enums.php';
}

/**
 * @requires PHP >= 8.1.0
 */
#[RequiresPhp('>= 8.1.0')]
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
