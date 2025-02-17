<?php

namespace Orchestra\Testbench\Tests\Support;

use Orchestra\Sidekick\FluentDecorator;

it('can utilise fluent features', function () {
    [$fluent, $attributes] = newFluent();

    expect(isset($fluent['testbench']))->toBeTrue();
    expect(isset($fluent['class']))->toBeTrue();
    expect(isset($fluent['workbench']))->toBeFalse();

    expect($fluent['testbench'])->toBeTrue();
    expect($fluent['workbench'])->toBeNull();

    expect($attributes)->toBe($fluent->getAttributes());
    expect($attributes)->toBe($fluent->toArray());
    expect(json_encode($attributes))->toBe($fluent->toJson());
    expect($attributes)->toBe($fluent->jsonSerialize());
});

it('can utilise fluent as object', function () {
    [$fluent] = newFluent();

    expect(isset($fluent->laravel))->toBeFalse();
    expect(isset($fluent->file))->toBeFalse();
    expect($fluent->laravel)->toBeNull();
    expect($fluent->file)->toBeNull();

    $fluent->laravel = '12.0.0';
    $fluent->file = __FILE__;

    expect(isset($fluent->laravel))->toBeTrue();
    expect(isset($fluent->file))->toBeTrue();
    expect($fluent->laravel)->toBe('12.0.0');
    expect($fluent->file)->toBe(__FILE__);

    unset($fluent->file);

    expect(isset($fluent->laravel))->toBeTrue();
    expect(isset($fluent->file))->toBeFalse();
});

it('can utilise fluent as array', function () {
    [$fluent] = newFluent();

    expect(isset($fluent['laravel']))->toBeFalse();
    expect(isset($fluent['file']))->toBeFalse();
    expect($fluent['laravel'])->toBeNull();
    expect($fluent['file'])->toBeNull();

    $fluent['laravel'] = '12.0.0';
    $fluent['file'] = __FILE__;

    expect(isset($fluent['laravel']))->toBeTrue();
    expect(isset($fluent['file']))->toBeTrue();
    expect($fluent['laravel'])->toBe('12.0.0');
    expect($fluent['file'])->toBe(__FILE__);

    unset($fluent['file']);

    expect(isset($fluent['laravel']))->toBeTrue();
    expect(isset($fluent['file']))->toBeFalse();
});

it('can set fluent attribute using method call', function () {
    [$fluent] = newFluent();

    expect(isset($fluent['laravel']))->toBeFalse();
    expect($fluent['laravel'])->toBeNull();

    expect($fluent->laravel('12.0.0'))->toBeInstanceOf(FluentDecorator::class);

    expect(isset($fluent['laravel']))->toBeTrue();
    expect($fluent['laravel'])->toBe('12.0.0');
});

function newFluent(): array {
    $attributes = ['testbench' => true, 'class' => __CLASS__];

    return [
        new class($attributes) extends FluentDecorator
        {
            // ...
        },
        $attributes,
    ];
}
