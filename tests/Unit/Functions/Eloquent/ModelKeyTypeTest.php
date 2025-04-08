<?php

namespace Orchestra\Sidekick\Tests\Functions\Eloquent;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\Eloquent\model_key_type;

class ModelKeyTypeTest extends TestCase
{
    /**
     * @dataProvider modelDataProvider
     */
    #[DataProvider('modelDataProvider')]
    public function test_it_can_detect_model(Model|string $model, string $exceptedType)
    {
        $this->assertSame($exceptedType, model_key_type($model));
    }

    public static function modelDataProvider()
    {
        yield [User::class, 'int'];
        yield [new DatabaseNotification, 'string'];
    }

    public function test_it_cant_detect_key_type_when_not_given_an_instance_of_eloquent()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Given $model is not an instance of [Illuminate\Database\Eloquent\Model].');

        $table = model_key_type(new class
        {
            //
        });
    }
}
