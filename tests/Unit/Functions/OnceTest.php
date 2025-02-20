<?php

namespace Orchestra\Sidekick\Tests\Unit\Functions;

use Illuminate\Container\Container;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function Orchestra\Sidekick\once;

class OnceTest extends TestCase
{
    protected $app;

    /** {@inheritDoc} */
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->app = new Container;
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function tearDown(): void
    {
        unset($this->app);

        parent::tearDown();
    }

    /**
     * @dataProvider onceDataProvider
     */
    #[DataProvider('onceDataProvider')]
    public function test_it_can_cache_the_result($value)
    {
        $counter = 0;

        $response = once(function () use ($value, &$counter) {
            $counter++;

            return $value;
        });

        $this->assertSame(0, $counter);

        $this->assertSame($value, $response());
        $this->assertSame(1, $counter);

        $this->assertSame($value, $response());
        $this->assertSame(1, $counter);
    }

    public static function onceDataProvider()
    {
        yield [null];
        yield ['Workbench'];
        yield [100_000];
        yield [['foo' => 'bar']];
    }

    public function test_it_can_cache_object()
    {
        $stub = once(function () {
            $this->app->instance(__CLASS__.'.once', time());
        });
        $stub2 = once(function () {
            $this->app->instance(__CLASS__.'.once2', $response = time());

            return tap(time(), function ($response) {
                $this->app->instance(__CLASS__.'.once2', $response);
            });
        });

        $this->assertFalse($this->app->bound(__CLASS__.'.once'));
        $this->assertFalse($this->app->bound(__CLASS__.'.once2'));

        value($stub);

        $this->assertTrue($this->app->bound(__CLASS__.'.once'));
        $this->assertFalse($this->app->bound(__CLASS__.'.once2'));

        tap($this->app[__CLASS__.'.once'], function ($time) use ($stub) {
            value($stub);
            $this->assertSame($time, $this->app[__CLASS__.'.once']);
        });

        $response = value($stub2);

        $this->assertTrue($this->app->bound(__CLASS__.'.once2'));
        $this->assertSame($response, $this->app[__CLASS__.'.once2']);
    }
}
