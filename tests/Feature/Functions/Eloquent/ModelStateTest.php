<?php

namespace Orchestra\Sidekick\Tests\Feature\Functions\Eloquent;

use Carbon\CarbonImmutable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Sidekick\Eloquent\Watcher;
use Orchestra\Sidekick\SensitiveValue;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Factories\UserFactory;
use Orchestra\Testbench\TestCase;

use function Orchestra\Sidekick\Eloquent\model_snapshot;
use function Orchestra\Sidekick\Eloquent\model_state;

#[WithConfig('db.default', 'testing')]
#[WithMigration]
class ModelStateTest extends TestCase
{
    use RefreshDatabase;

    /** {@inheritDoc} */
    #[\Override]
    protected function tearDown(): void
    {
        Watcher::flushState();

        parent::tearDown();
    }

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

        [$original, $changes] = model_state($user);

        $this->assertNull($original);
        $this->assertSame(['name', 'email', 'password', 'created_at'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki', $changes['name']);
        $this->assertSame('crynobone@gmail.com', $changes['email']);
        $this->assertSame($now->startOfSecond()->toJSON(), $changes['created_at']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);

        $this->assertSame([], array_keys($user->getChanges()));
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

        [$original, $changes] = model_state($user);

        $this->assertNull($original);
        $this->assertSame(['name', 'email', 'password', 'created_at', 'id'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki', $changes['name']);
        $this->assertSame('crynobone@gmail.com', $changes['email']);
        $this->assertSame($now->startOfSecond()->toJSON(), $changes['created_at']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);

        $this->assertSame([], array_keys($user->getChanges()));
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
        $user->setHidden(['password']);

        $user->name = 'Mior Muhammad Zaki bin Mior Khairuddin';
        $user->password = password_hash('password', PASSWORD_DEFAULT);
        $user->updated_at = $now;

        [$original, $changes] = model_state($user);

        $this->assertSame(['name', 'password', 'updated_at'], array_keys($original));
        $this->assertSame('Mior Muhammad Zaki', $original['name']);
        $this->assertSame($now->subMinutes(2)->startOfSecond()->toJSON(), $original['updated_at']);
        $this->assertInstanceOf(SensitiveValue::class, $original['password']);

        $this->assertSame(['name', 'password', 'updated_at'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki bin Mior Khairuddin', $changes['name']);
        $this->assertSame($now->startOfSecond()->toJSON(), $changes['updated_at']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);

        $this->assertSame([], array_keys($user->getChanges()));
    }

    public function test_it_can_detect_changes_after_updating_a_model()
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
        $user->setHidden(['password']);

        model_snapshot($user);

        $user->name = 'Mior Muhammad Zaki bin Mior Khairuddin';
        $user->password = password_hash('password', PASSWORD_DEFAULT);
        $user->updated_at = $now;

        [$original, $changes] = model_state($user);

        $this->assertSame(['name', 'password', 'updated_at'], array_keys($original));
        $this->assertSame('Mior Muhammad Zaki', $original['name']);
        $this->assertSame($now->subMinutes(2)->startOfSecond()->toJSON(), $original['updated_at']);
        $this->assertInstanceOf(SensitiveValue::class, $original['password']);

        $this->assertSame(['name', 'password', 'updated_at'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki bin Mior Khairuddin', $changes['name']);
        $this->assertSame($now->startOfSecond()->toJSON(), $changes['updated_at']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);
    }

    public function test_it_can_detect_changes_after_updating_a_model_without_snapshot()
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
        $user->setHidden(['password']);

        $user->name = 'Mior Muhammad Zaki bin Mior Khairuddin';
        $user->password = password_hash('password', PASSWORD_DEFAULT);
        $user->updated_at = $now;

        [$original, $changes] = model_state($user);

        $this->assertSame(['name', 'password', 'updated_at'], array_keys($original));
        $this->assertSame('Mior Muhammad Zaki', $original['name']);
        $this->assertSame($now->subMinutes(2)->startOfSecond()->toJSON(), $original['updated_at']);
        $this->assertInstanceOf(SensitiveValue::class, $original['password']);

        $this->assertSame(['name', 'password', 'updated_at'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki bin Mior Khairuddin', $changes['name']);
        $this->assertSame($now->startOfSecond()->toJSON(), $changes['updated_at']);
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

        [$original, $changes] = model_state($user, withTimestamps: false);

        $this->assertNull($original);
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
        $user->setHidden(['password']);

        $user->name = 'Mior Muhammad Zaki bin Mior Khairuddin';
        $user->password = password_hash('password', PASSWORD_DEFAULT);
        $user->updateTimestamps();

        [$original, $changes] = model_state($user, withTimestamps: false);

        $this->assertSame(['name', 'password'], array_keys($original));
        $this->assertSame('Mior Muhammad Zaki', $original['name']);
        $this->assertInstanceOf(SensitiveValue::class, $original['password']);

        $this->assertSame(['name', 'password'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki bin Mior Khairuddin', $changes['name']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);
    }

    /**
     * Create an instance of user model.
     */
    protected function newUserModel()
    {
        return (new User)->setHidden(['password']);
    }
}
