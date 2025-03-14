<?php

namespace Orchestra\Sidekick\Tests\Functions\Eloquent;

use Illuminate\Foundation\Auth\User;
use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\Eloquent\model_exists;

class ModelExistsTest extends TestCase
{
    public function test_it_can_detect_existing_model()
    {
        $user = new User;
        $user->exists = true;

        $this->assertTrue(model_exists($user));
    }

    public function test_it_can_detect_none_existing_model()
    {
        $user = new User;

        $this->assertFalse(model_exists($user));
    }
}
