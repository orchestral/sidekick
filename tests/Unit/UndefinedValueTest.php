<?php

use Orchestra\Sidekick\UndefinedValue;

it('can be resolved', function () {
    $stub = new UndefinedValue;

    expect($stub)->toBeInstanceOf(UndefinedValue::class);
    expect(UndefinedValue::equalsTo($stub))->toBeTrue();
    expect(UndefinedValue::equalsTo(null))->toBeTrue();
    expect(UndefinedValue::equalsTo('Testbench'))->toBeFalse();
    expect(UndefinedValue::equalsTo(''))->toBeFalse();
});

it('can be serialized', function () {
    $stub = new UndefinedValue;

    expect($stub->jsonSerialize())->toBeNull();
    expect(json_encode(['content' => $stub], true))->toBe('{"content":null}');
});
