<?php

namespace Orchestra\Sidekick\Tests\Feature\Eloquent\Functions;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\TestCase;
use function Orchestra\Sidekick\Eloquent\model_from;

#[WithConfig('database.default', 'testing')]
class ModelFromTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_resolve_a_model_from_attributes()
    {
        $now = CarbonImmutable::now();

        $user = model_from(User::class, ['name' => 'Mior Muhammad Zaki', 'email_verified_at' => $now]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertFalse($user->exists);
        $this->assertSame('Mior Muhammad Zaki', $user->name);
        $this->assertInstanceOf('Illuminate\Support\Carbon', $user->email_verified_at);
        $this->assertSame($now->toJSON(), $user->email_verified_at->toJSON());
    }
}
