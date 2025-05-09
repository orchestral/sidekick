<?php

namespace Orchestra\Sidekick\Tests\Feature\Functions\Eloquent;

use App\Models\User;
use Carbon\CarbonImmutable;
use Orchestra\Sidekick\SensitiveValue;
use Orchestra\Sidekick\Tests\Concerns\InteractsWithDatabase;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\Eloquent\model_state;

class ModelStateTest extends TestCase
{
    use InteractsWithDatabase;

    /** {@inheritDoc} */
    #[\Override]
    protected function setUp(): void
    {
        $this->setUpTestEnvironmentForDatabase();
    }

    /** {@inheritDoc} */
    protected function createDatabaseSchema($schema): void
    {
        //
    }

    public function test_it_can_detect_changes_on_creating_a_model()
    {
        $user = (new User)->forceFill([
            'name' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
            'password' => $password = password_hash('secret', PASSWORD_DEFAULT),
        ]);

        [$original, $changes] = model_state($user);

        $this->assertNull($original);
        $this->assertSame(['name', 'email', 'password'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki', $changes['name']);
        $this->assertSame('crynobone@gmail.com', $changes['email']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);
    }

    public function test_it_can_detect_changes_on_recently_created_model()
    {
        $user = (new User)->forceFill([
            'name' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
            'password' => $password = password_hash('secret', PASSWORD_DEFAULT),
        ]);

        $user->syncOriginal();
        $user->exists = true;
        $user->wasRecentlyCreated = true;

        [$original, $changes] = model_state($user);

        $this->assertNull($original);
        $this->assertSame(['name', 'email', 'password'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki', $changes['name']);
        $this->assertSame('crynobone@gmail.com', $changes['email']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);
    }

    public function test_it_can_detect_changes_on_updating_a_model()
    {
        $user = (new User([
            'name' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
            'password' => $password = password_hash('secret', PASSWORD_DEFAULT),
        ]));

        $user->syncOriginal();

        $user->exists = true;
        $user->name = 'Mior Muhammad Zaki bin Mior Khairuddin';
        $user->password = password_hash('password', PASSWORD_DEFAULT);

        [$original, $changes] = model_state($user);

        $this->assertSame(['name' => 'Mior Muhammad Zaki'], $original);
        $this->assertSame(['name', 'password'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki bin Mior Khairuddin', $changes['name']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);
    }

    public function test_it_ignores_timestamps_on_creating_a_model()
    {
        $now = CarbonImmutable::now();

        $user = (new User)->forceFill([
            'name' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
            'password' => $password = password_hash('secret', PASSWORD_DEFAULT),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        [$original, $changes] = model_state($user);

        $this->assertNull($original);
        $this->assertSame(['name', 'email', 'password'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki', $changes['name']);
        $this->assertSame('crynobone@gmail.com', $changes['email']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);
    }

    public function test_it_ignores_timestamps_on_updating_a_model()
    {
        $now = CarbonImmutable::now();

        $user = (new User([
            'name' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
            'password' => $password = password_hash('secret', PASSWORD_DEFAULT),
            'created_at' => $now,
            'updated_at' => $now,
        ]));

        $user->syncOriginal();

        $user->exists = true;
        $user->name = 'Mior Muhammad Zaki bin Mior Khairuddin';
        $user->password = password_hash('password', PASSWORD_DEFAULT);
        $user->updateTimestamps();

        [$original, $changes] = model_state($user);

        $this->assertSame(['name' => 'Mior Muhammad Zaki'], $original);
        $this->assertSame(['name', 'password'], array_keys($changes));
        $this->assertSame('Mior Muhammad Zaki bin Mior Khairuddin', $changes['name']);
        $this->assertInstanceOf(SensitiveValue::class, $changes['password']);
    }
}
