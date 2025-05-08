<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions\Eloquent;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
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

        [$visibleSummaries, $hiddenSummaries] = Collection::make($summaries)->partition(
            fn ($value, $key) => ! \in_array($key, $hiddens, true)
        );

        $this->assertEquals($expected, $visibleSummaries->all());

        $this->assertSame($hiddenSummaries->count(), \count($hiddens));

        foreach ($hiddens as $key) {
            $this->assertInstanceOf(SensitiveValue::class, $summaries[$key]);
        }

        $this->assertSame($hiddenSummaries->count(), \count($hiddens));
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
            ['name' => 'Mior Muhammad Zaki'],
        ];
    }
}
