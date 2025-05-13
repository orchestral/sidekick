<?php

namespace Orchestra\Sidekick\Eloquent;

use Illuminate\Database\Eloquent\Model;
use WeakMap;

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
    public static function snapshot(Model $model): array
    {
        $original = $model->getRawOriginal();

        static::store()[$model] = $original;

        return $original;
    }

    /**
     * Get attributes diff state from a model.
     *
     * @api
     *
     * @return array<string, mixed>|null
     */
    public static function fetch(Model $model): ?array
    {
        if (! \is_null(($original = static::store()[$model] ?? null))) {
            return $original;
        }

        return $model->isDirty() ? $model->getRawOriginal() : null;
    }

    /**
     * Flush the instance states.
     */
    public static function flushState(): void
    {
        static::$cache = null;
    }
}
