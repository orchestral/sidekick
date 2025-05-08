<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions\Eloquent;

use Carbon\CarbonImmutable;
use Orchestra\Sidekick\SensitiveValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\Eloquent\summarize_changes;

class SummarizeChangesTest extends TestCase
{
    /**
     * @dataProvider valuesDataProvider
     */
    #[DataProvider('valuesDataProvider')]
    public function test_it_can_summarize_changes(array $given, ?array $hiddenKeys, array $expected)
    {
        $hiddens = $hiddenKeys ?? [];

        $summaries = summarize_changes($given, $hiddens);

        $this->assertArrayIsEqualToArrayIgnoringListOfKeys($expected, $summaries, $hiddens);

        foreach ($hiddens as $key) {
            $this->assertInstanceOf(SensitiveValue::class, $summaries[$key]);
        }
    }

    public static function valuesDataProvider()
    {
        $now = CarbonImmutable::now();
        $password = password_hash('secret', PASSWORD_DEFAULT);

        yield [
            ['name' => 'Mior Muhammad Zaki', 'password' => $password, 'created_at' => $now],
            null,
            ['name' => 'Mior Muhammad Zaki', 'password' => $password, 'created_at' => $now->toJSON()],
        ];

        yield [
            ['name' => 'Mior Muhammad Zaki', 'password' => $password],
            ['password'],
            ['name' => 'Mior Muhammad Zaki', 'password' => '*******'],
        ];
    }
}
