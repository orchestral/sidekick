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
 * Transform relative path.
 *
 * @api
 *
 * @param  string  $path
 * @param  string  $workingPath
 * @return string
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
 * @param  string  $version
 * @param  string|null  $operator
 * @return int|bool
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

    /**
     * @var string $laravel
     *
     * @phpstan-ignore argument.templateType
     */
    $laravel = transform(
        Application::VERSION,
        fn (string $version) => match (true) {
            $version === '12.x-dev' => '12.0.0',
            default => $version,
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
 * @param  string  $version
 * @param  string|null  $operator
 * @return int|bool
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


/**
 * Determine the PHP Binary.
 *
 * @api
 *
 * @return string
 *
 * @codeCoverageIgnore
 */
function php_binary(): string
{
    return (new PhpExecutableFinder)->find(false) ?: 'php';
}
