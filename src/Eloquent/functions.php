<?php

namespace Orchestra\Sidekick\Eloquent;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

if (! \function_exists('Orchestra\Sidekick\Eloquent\column_name')) {
    /**
     * Get qualify column name from Eloquent model.
     *
     * @param  class-string<\Illuminate\Database\Eloquent\Model>|\Illuminate\Database\Eloquent\Model  $model
     *
     * @throws \InvalidArgumentException
     */
    function column_name($model, string $attribute): string
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

if (! \function_exists('Orchestra\Sidekick\Eloquent\model_exists')) {
    /**
     * Check whether given $model exists.
     *
     * @param  \Illuminate\Database\Eloquent\Model|mixed  $model
     */
    function model_exists($model): bool
    {
        return $model instanceof Model && $model->exists === true;
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\table_name')) {
    /**
     * Get table name from Eloquent model.
     *
     * @param  class-string<\Illuminate\Database\Eloquent\Model>|\Illuminate\Database\Eloquent\Model  $model
     *
     * @throws \InvalidArgumentException
     */
    function table_name($model): string
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
