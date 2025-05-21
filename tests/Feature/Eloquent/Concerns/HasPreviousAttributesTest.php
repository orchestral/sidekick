<?php

namespace Orchestra\Sidekick\Tests\Feature\Eloquent\Concerns;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\RequiresLaravel;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase;

#[RequiresLaravel('<12.15.0')]
#[WithConfig('database.default', 'testing')]
#[WithMigration]
class HasPreviousAttributesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_does_not_capture_changes_on_created_model()
    {
        $user = (new User)->forceFill([
            'name' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
            'password' => $password = password_hash('secret', PASSWORD_DEFAULT),
        ]);

        $user->save();

        $this->assertSame([], $user->getPrevious());
        $this->assertSame([], $user->getChanges());
    }

    public function test_it_can_capture_previous_on_updated_model()
    {
        UserFactory::new()->create([
            'name' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
            'password' => $password = password_hash('secret', PASSWORD_DEFAULT),
        ]);

        $user = User::query()->latest()->first();

        $user->name = 'Mior Muhammad Zaki bin Mior Khairuddin';
        $user->password = $newPassword = password_hash('password', PASSWORD_DEFAULT);

        $user->withoutTimestamps(function () use ($user) {
            $user->save();
        });

        $this->assertSame(['name' => 'Mior Muhammad Zaki', 'password' => $password], $user->getPrevious());
        $this->assertSame(['name' => 'Mior Muhammad Zaki bin Mior Khairuddin', 'password' => $newPassword], $user->getChanges());
    }
}
