<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions\Eloquent;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function Orchestra\Sidekick\Eloquent\summarize_changes;

class SummarizeChangesTest extends TestCase
{
    /**
     * @dataProvider valuesDataProvider
     */
    #[DataProvider('valuesDataProvider')]
    public function test_it_can_summarize_changes(array $given, ?array $hiddens, array $expected)
    {
        $this->assertSame($expected, summarize_changes($given, $hiddens ?? []));
    }

    public static function valuesDataProvider()
    {
        $now = CarbonImmutable::now();
        $password = password_hash('secret', PASSWORD_DEFAULT);

        yield [
            ['name' => 'Mior Muhammad Zaki', 'password' => $password, 'created_at' => $now],
            null,
            ['name' => 'Mior Muhammad Zaki', 'password' => $password, 'created_at' => $now->jsonSerialize()],
        ];
    }
}
