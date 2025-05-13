<?php

namespace Orchestra\Sidekick\Eloquent;

use Illuminate\Database\Eloquent\Model;
use WeakMap;

/**
 * @internal
 */
class Watcher
{
    /**
     * The cache instance.
     *
     * @var \WeakMap<\Illuminate\Database\Eloquent\Model, array<string, mixed>>|null
     */
    protected static ?WeakMap $cache = null;

    /**
     * Get the watcher store.
     *
     * @return \WeakMap<\Illuminate\Database\Eloquent\Model, array<string, mixed>>
     */
    public static function store(): WeakMap
    {
        /** @phpstan-ignore assign.propertyType,return.type */
        return static::$cache ??= new WeakMap;
    }

    /**
     * Submit a model snapshot.
     *
     * @return array<string, mixed>
     */
    public static function snapshot(Model $model): ?array
    {
        $original = $model->getRawOriginal();

        $response = match (true) {
            isset(static::store()[$model]) => static::store()[$model],

            // When the model is already saved without existing snapshot, original
            // is already sync with changes and it's no longer possible to
            // provide the diff.
            $model->isDirty() === false => null,

            default => $original,
        };

        static::store()[$model] = $original;

        return $response;
    }

    /**
     * Flush the instance states.
     */
    public static function flushState(): void
    {
        static::$cache = null;
    }
}
