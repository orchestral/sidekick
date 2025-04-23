<?php

namespace Orchestra\Sidekick\Tests\Functions\Eloquent;

use App\Models\User;
use Illuminate\Support\Fluent;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\Eloquent\table_name;

class TableNameTest extends TestCase
{
    public function test_it_can_translate_table_name()
    {
        $table = table_name(User::class);

        $this->assertSame('users', $table);
    }

    public function test_it_can_translate_table_name_when_given_an_instance_of_eloquent()
    {
        $table = table_name(new User);

        $this->assertSame('users', $table);
    }

    public function test_it_cant_translate_table_name_when_not_given_an_instance_of_eloquent()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Given $model is not an instance of [Illuminate\Database\Eloquent\Model].');

        $table = table_name(Fluent::class);
    }
}
