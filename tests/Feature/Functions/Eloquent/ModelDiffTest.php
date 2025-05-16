<?php

namespace Orchestra\Sidekick\Tests\Feature\Functions\Eloquent;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Sidekick\SensitiveValue;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Factories\UserFactory;
use Orchestra\Testbench\TestCase;

use function Orchestra\Sidekick\Eloquent\model_diff;

#[WithConfig('db.default', 'testing')]
#[WithMigration]
class ModelDiffTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_detect_changes_on_creating_a_model()
    {
        $now = CarbonImmutable::now();

        $user = $this->newUserModel()->forceFill([
            'name' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
            'password' => $password = password_hash('secret', PASSWORD_DEFAULT),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $changes = model_diff($user);

        $this->assertSame(['name', 'email', 'password', 'created_at'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki', $changes['name']);
        $this->assertSame('crynobone@gmail.com', $changes['email']);
        $this->assertSame($now->startOfSecond()->toJSON(), $changes['created_at']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);
    }

    public function test_it_can_detect_changes_on_recently_created_model()
    {
        $now = CarbonImmutable::now();

        $user = $this->newUserModel()->forceFill([
            'name' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
            'password' => $password = password_hash('secret', PASSWORD_DEFAULT),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $user->save();

        $changes = model_diff($user);

        $this->assertSame(['name', 'email', 'password', 'created_at', 'id'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki', $changes['name']);
        $this->assertSame('crynobone@gmail.com', $changes['email']);
        $this->assertSame($now->startOfSecond()->toJSON(), $changes['created_at']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);
    }

    public function test_it_can_detect_changes_on_updating_a_model()
    {
        $now = CarbonImmutable::now();

        UserFactory::new()->create([
            'name' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
            'password' => $password = password_hash('secret', PASSWORD_DEFAULT),
            'created_at' => $now->subMinutes(2),
            'updated_at' => $now->subMinutes(2),
        ]);

        $user = User::query()->latest()->first();

        $user->name = 'Mior Muhammad Zaki bin Mior Khairuddin';
        $user->password = password_hash('password', PASSWORD_DEFAULT);
        $user->updated_at = $now;

        $changes = model_diff($user);

        $this->assertSame(['name', 'password', 'updated_at'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki bin Mior Khairuddin', $changes['name']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);
    }

    public function test_it_can_detect_changes_after_updating_a_model()
    {
        $now = CarbonImmutable::now();

        UserFactory::new()->create([
            'name' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
            'password' => $password = password_hash('secret', PASSWORD_DEFAULT),
            'created_at' => $now,
        ]);

        $user = User::query()->latest()->first();

        $user->name = 'Mior Muhammad Zaki bin Mior Khairuddin';
        $user->password = password_hash('password', PASSWORD_DEFAULT);

        $user->save();

        $changes = model_diff($user);

        $this->assertSame(['name', 'password'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki bin Mior Khairuddin', $changes['name']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);
    }

    public function test_it_ignores_timestamps_on_creating_a_model()
    {
        $now = CarbonImmutable::now();

        $user = $this->newUserModel()->forceFill([
            'name' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
            'password' => $password = password_hash('secret', PASSWORD_DEFAULT),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $changes = model_diff($user, withTimestamps: false);

        $this->assertSame(['name', 'email', 'password'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki', $changes['name']);
        $this->assertSame('crynobone@gmail.com', $changes['email']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);
    }

    public function test_it_ignores_timestamps_on_updating_a_model()
    {
        $now = CarbonImmutable::now();

        UserFactory::new()->create([
            'name' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
            'password' => $password = password_hash('secret', PASSWORD_DEFAULT),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $user = User::query()->latest()->first();

        $user->name = 'Mior Muhammad Zaki bin Mior Khairuddin';
        $user->password = password_hash('password', PASSWORD_DEFAULT);
        $user->updateTimestamps();

        $changes = model_diff($user, withTimestamps: false);

        $this->assertSame(['name', 'password'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki bin Mior Khairuddin', $changes['name']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);
    }

    public function test_it_can_excludes_given_fields()
    {
        $now = CarbonImmutable::now();

        $user = $this->newUserModel()->forceFill([
            'name' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $changes = model_diff($user, ['email'], false);

        $this->assertSame(['name'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki', $changes['name']);
    }

    /**
     * Create an instance of user model.
     */
    protected function newUserModel()
    {
        return (new User)->setHidden(['password']);
    }
}
