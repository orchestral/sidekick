<?php

use Illuminate\Container\Container;

use function Orchestra\Sidekick\once;

beforeEach(function () {
    $this->app = new Container;
});

afterEach(function () {
    unset($this->app);
});

it('can use `once()`', function ($value) {
    $counter = 0;

    $response = once(function () use ($value, &$counter) {
        $counter++;

        return $value;
    });

    expect($counter)->toBe(0);

    expect($response())->toBe($value);
    expect($counter)->toBe(1);

    expect($response())->toBe($value);
    expect($counter)->toBe(1);
})->with([
    [null],
    ['workbench'],
    [100_000],
]);

it('can only resolve value once', function () {
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
});
