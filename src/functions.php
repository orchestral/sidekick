<?php

namespace Orchestra\Sidekick;

use Illuminate\Foundation\Application;
use PHPUnit\Runner\Version;
use RuntimeException;

/**
 * Join the given paths together.
 */
function join_paths(?string $basePath, string ...$paths): string
{
    foreach ($paths as $index => $path) {
        if (empty($path) && $path !== '0') {
            unset($paths[$index]);
        } else {
            $paths[$index] = DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR);
        }
    }

    return $basePath.implode('', $paths);
}

/**
 * Transform realpath to alias path.
 *
 * @api
 */
function transform_realpath_to_relative(string $path, ?string $workingPath = null, string $prefix = ''): string
{
    $separator = DIRECTORY_SEPARATOR;

    if (! \is_null($workingPath)) {
        return str_replace(rtrim($workingPath, $separator).$separator, $prefix.$separator, $path);
    }

    $laravelPath = base_path();
    $workbenchPath = workbench_path();
    $packagePath = package_path();

    return match (true) {
        str_starts_with($path, $laravelPath) => str_replace($laravelPath.$separator, '@laravel'.$separator, $path),
        str_starts_with($path, $workbenchPath) => str_replace($workbenchPath.$separator, '@workbench'.$separator, $path),
        str_starts_with($path, $packagePath) => str_replace($packagePath.$separator, '.'.$separator, $path),
        ! empty($prefix) => implode($separator, [$prefix, ltrim($path, $separator)]),
        default => $path,
    };
}

/**
 * Transform relative path.
 *
 * @api
 */
function transform_relative_path(string $path, string $workingPath): string
{
    return str_starts_with($path, './')
        ? rtrim($workingPath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.mb_substr($path, 2)
        : $path;
}

/**
 * Laravel version compare.
 *
 * @api
 *
 * @template TOperator of string|null
 *
 * @phpstan-param  TOperator  $operator
 *
 * @phpstan-return (TOperator is null ? int : bool)
 *
 * @codeCoverageIgnore
 */
function laravel_version_compare(string $version, ?string $operator = null): int|bool
{
    if (! class_exists(Application::class)) {
        throw new RuntimeException('Unable to verify Laravel Framework version');
    }

    /** @var string $laravel */
    $laravel = transform(
        Application::VERSION,
        fn (string $version) => match (true) {
            $version === '12.x-dev' => '12.0.0',
            default => $version, // @phpstan-ignore identical.alwaysTrue
        }
    );

    if (\is_null($operator)) {
        return version_compare($laravel, $version);
    }

    return version_compare($laravel, $version, $operator);
}

/**
 * PHPUnit version compare.
 *
 * @api
 *
 * @template TOperator of string|null
 *
 * @throws \RuntimeException
 *
 * @phpstan-param  TOperator  $operator
 *
 * @phpstan-return (TOperator is null ? int : bool)
 *
 * @codeCoverageIgnore
 */
function phpunit_version_compare(string $version, ?string $operator = null): int|bool
{
    if (! class_exists(Version::class)) {
        throw new RuntimeException('Unable to verify PHPUnit version');
    }

    /** @var string $phpunit */
    $phpunit = transform(
        Version::id(),
        fn (string $version) => match (true) {
            str_starts_with($version, '12.1-') => '12.1.0',
            default => $version,
        }
    );

    if (\is_null($operator)) {
        return version_compare($phpunit, $version);
    }

    return version_compare($phpunit, $version, $operator);
}
