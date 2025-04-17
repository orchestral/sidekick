<?php

namespace Orchestra\Sidekick\Tests\Functions\Eloquent;

use App\Models\User;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\Eloquent\column_name;

class ColumnNameTest extends TestCase
{
    public function test_it_can_translate_column_name()
    {
        $column = column_name(User::class, 'email');

        $this->assertSame('users.email', $column);
    }

    public function test_it_can_translate_column_name_when_given_an_instance_of_eloquent()
    {
        $column = column_name(new User, 'email');

        $this->assertSame('users.email', $column);
    }

    public function test_it_cant_translate_column_name_when_not_given_an_instance_of_eloquent()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Given $model is not an instance of [Illuminate\Database\Eloquent\Model].');

        $column = column_name(new class
        {
            //
        }, 'email');
    }
}
