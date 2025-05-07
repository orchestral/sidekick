<?php

namespace Orchestra\Sidekick\Eloquent;

use BackedEnum;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Orchestra\Sidekick\SensitiveValue;
use Stringable;
use Throwable;

if (! \function_exists('Orchestra\Sidekick\Eloquent\column_name')) {
    /**
     * Get qualify column name from Eloquent model.
     *
     * @api
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
     * @api
     *
     * @template TPivotModel of (\Illuminate\Database\Eloquent\Model&\Illuminate\Database\Eloquent\Relations\Concerns\AsPivot)|\Illuminate\Database\Eloquent\Relations\Pivot
     *
     * @param  TPivotModel|class-string<TPivotModel>  $model
     *
     * @throws \InvalidArgumentException
     */
    function is_pivot_model(Pivot|Model|string $model): bool
    {
        if (\is_string($model)) {
            $model = new $model;
        }

        if (! $model instanceof Model) {
            throw new InvalidArgumentException(\sprintf('Given $model is not an instance of [%s|%s].', Model::class, Pivot::class));
        }

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
     * @api
     *
     * @param  \Illuminate\Database\Eloquent\Model|mixed  $model
     */
    function model_exists(mixed $model): bool
    {
        return $model instanceof Model && $model->exists === true;
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\model_key_type')) {
    /**
     * Check whether given $model key type.
     *
     * @api
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

if (! \function_exists('Orchestra\Sidekick\Eloquent\model_state')) {
    /**
     * Get attributes original and changed state from a model.
     *
     * @api
     *
     * @template TPivotModel of (\Illuminate\Database\Eloquent\Model&\Illuminate\Database\Eloquent\Relations\Concerns\AsPivot)|\Illuminate\Database\Eloquent\Relations\Pivot
     *
     * @param  TPivotModel  $model
     * @return array{0: array<string, mixed>|null, 1: array<string, mixed>}
     */
    function model_state(Pivot|Model $model): array
    {
        $copy = clone $model;
        $hiddenAttributes = $model->getHidden();

        $copy->setHidden([]);

        $sanitizeValues = function (array $attributes) use ($hiddenAttributes): array {
            return Collection::make($attributes)
                ->map(
                    static fn (mixed $value, string $attribute) => \in_array($attribute, $hiddenAttributes, true)
                        ? new SensitiveValue($value)
                        : normalize_value($value)
                )->all();
        };

        if (! model_exists($model) || $model->wasRecentlyCreated == true) {
            $original = null;
            $changes = $sanitizeValues($copy->attributesToArray());

            return [$original, $changes];
        }

        $changes = $sanitizeValues($copy->getDirty());
        $original = $sanitizeValues(
            array_intersect_key($model->newInstance()->setRawAttributes($model->getRawOriginal())->attributesToArray(), $changes)
        );

        return [$original, $changes];
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\normalize_value')) {
    /**
     * Normalize the given value to be store to database as scalar.
     *
     * @api
     *
     * @return scalar
     */
    function normalize_value(mixed $value): mixed
    {
        if ($value instanceof BackedEnum) {
            return $value->value;
        } elseif (\is_object($value) && $value instanceof Stringable) {
            return (string) $value;
        } elseif (\is_object($value) || \is_array($value)) {
            try {
                return json_encode($value);
            } catch (Throwable $e) { // @phpstan-ignore catch.neverThrown
                return $value;
            }
        }

        return $value;
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\table_name')) {
    /**
     * Get table name from Eloquent model.
     *
     * @api
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
