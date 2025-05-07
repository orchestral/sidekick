<?php

namespace Orchestra\Sidekick\Tests\Feature\Functions\Eloquent;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Orchestra\Sidekick\SensitiveValue;
use Orchestra\Sidekick\Tests\Concerns\InteractsWithDatabase;
use PHPUnit\Framework\TestCase;
use function Orchestra\Sidekick\Eloquent\model_state;

class ModelStateTest extends TestCase
{
    use InteractsWithDatabase;

    /** {@inheritDoc} */
    protected function createDatabaseSchema($schema): void
    {
        //
    }

    public function test_it_can_validate_changes_on_creating_a_model()
    {
        $user = (new User)->forceFill([
            'name' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
            'password' => $password = password_hash('secret', PASSWORD_DEFAULT),
        ]);

        [$original, $changes] = model_state($user);

        $this->assertNull($original);
        $this->assertSame('Mior Muhammad Zaki', $changes['name']);
        $this->assertSame('crynobone@gmail.com', $changes['email']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);
    }
}
