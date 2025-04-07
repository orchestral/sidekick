<?php

namespace Orchestra\Sidekick\Tests\Functions\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
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
}
