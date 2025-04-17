<?php

namespace Orchestra\Sidekick\Eloquent;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;
use Illuminate\Database\Eloquent\Relations\Pivot;
use InvalidArgumentException;

if (! \function_exists('Orchestra\Sidekick\Eloquent\column_name')) {
    /**
     * Get qualify column name from Eloquent model.
     *
     * @param  \Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>  $model
     *
     * @throws \InvalidArgumentException
     */
    function column_name(Model|string $model, string $attribute): string
    {
        if (\is_string($model)) {
            $model = new $model;
        }

        if (! $model instanceof Model) {
            throw new InvalidArgumentException(\sprintf('Given $model is not an instance of [%s].', Model::class));
        }

        return $model->qualifyColumn($attribute);
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\is_pivot_model')) {
    /**
     * Determine if the given model is a pivot model.
     *
     * @param  (\Illuminate\Database\Eloquent\Model&\Illuminate\Database\Eloquent\Relations\Concerns\AsPivot)|\Illuminate\Database\Eloquent\Relations\Pivot  $model
     */
    function is_pivot_model(Model|Pivot $model): bool
    {
        if ($model instanceof Pivot) {
            return true;
        }

        return \in_array(AsPivot::class, class_uses_recursive($model), true);
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\model_exists')) {
    /**
     * Check whether given $model exists.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    function model_exists(Model $model): bool
    {
        return $model->exists === true;
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\model_key_type')) {
    /**
     * Check whether given $model key type.
     *
     * @param  \Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>  $model
     *
     * @throws \InvalidArgumentException
     */
    function model_key_type(Model|string $model): string
    {
        if (\is_string($model)) {
            $model = new $model;
        }

        if (! $model instanceof Model) {
            throw new InvalidArgumentException(\sprintf('Given $model is not an instance of [%s].', Model::class));
        }

        $uses = class_uses_recursive($model);

        if (\in_array(HasUlids::class, $uses, true)) {
            return 'ulid';
        } elseif (\in_array(HasUuids::class, $uses, true)) {
            return 'uuid';
        }

        return $model->getKeyType();
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\table_name')) {
    /**
     * Get table name from Eloquent model.
     *
     * @param  \Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>  $model
     *
     * @throws \InvalidArgumentException
     */
    function table_name(Model|string $model): string
    {
        if (\is_string($model)) {
            $model = new $model;
        }

        if (! $model instanceof Model) {
            throw new InvalidArgumentException(\sprintf('Given $model is not an instance of [%s].', Model::class));
        }

        return $model->getTable();
    }
}
