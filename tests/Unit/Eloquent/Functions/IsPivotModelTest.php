<?php

namespace Orchestra\Sidekick\Tests\Unit\Eloquent\Functions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Fluent;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\Eloquent\is_pivot_model;

class IsPivotModelTest extends TestCase
{
    /**
     * @dataProvider modelDataProvider
     */
    #[DataProvider('modelDataProvider')]
    public function test_it_can_detect_model(Model|string $model, bool $exceptedType)
    {
        $this->assertSame($exceptedType, is_pivot_model($model));
    }

    public static function modelDataProvider()
    {
        yield [new class extends \Illuminate\Database\Eloquent\Relations\Pivot
        {
            // ...
        }, true];

        yield [new class extends \Illuminate\Database\Eloquent\Model
        {
            use \Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;

            // ...
        }, true];

        yield [new class extends \Illuminate\Database\Eloquent\Model
        {
            // ...
        }, false];
    }

    public function test_it_cant_detect_is_pivot_model_when_not_given_an_instance_of_eloquent()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Given $model is not an instance of [Illuminate\Database\Eloquent\Model|Illuminate\Database\Eloquent\Relations\Pivot].');

        is_pivot_model(Fluent::class);
    }
}
