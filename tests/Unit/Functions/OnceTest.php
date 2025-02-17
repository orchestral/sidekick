<?php

use function Orchestra\Sidekick\once;

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
