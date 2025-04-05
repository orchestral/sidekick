<?php

namespace Orchestra\Sidekick;

use Closure;
use Illuminate\Foundation\Application;
use PHPUnit\Runner\Version;
use RuntimeException;

if (! \function_exists('Orchestra\Sidekick\once')) {
    /**
     * Run callback only once.
     *
     * @api
     *
     * @param  mixed  $callback
     * @return \Closure():mixed
     */
    function once($callback): Closure
    {
        $response = new UndefinedValue;

        return function () use ($callback, &$response) {
            if ($response instanceof UndefinedValue) {
                $response = value($callback) ?? null;
            }

            return $response;
        };
    }
}

if (! \function_exists('Orchestra\Sidekick\join_paths')) {
    /**
     * Join the given paths together.
     *
     * @param  string|null  $basePath
     * @param  string  ...$paths
     * @return string
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
}

if (! \function_exists('Orchestra\Sidekick\is_symlink')) {
    /**
     * Determine if path is symlink for both Unix and Windows environment.
     *
     * @api
     *
     * @param  string  $path
     * @return bool
     */
    function is_symlink(string $path): bool
    {
        if (windows_os() && is_dir($path) && readlink($path) !== $path) {
            return true;
        } elseif (is_link($path)) {
            return true;
        }

        return false;
    }
}

if (! \function_exists('Orchestra\Sidekick\transform_relative_path')) {
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
}

if (! \function_exists('Orchestra\Sidekick\laravel_version_compare')) {
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
         */
        $laravel = transform(
            Application::VERSION,
            fn (string $version) => match ($version) {
                '13.x-dev' => '13.0.0',
                default => $version,
            }
        );

        if (\is_null($operator)) {
            return version_compare($laravel, $version);
        }

        return version_compare($laravel, $version, $operator);
    }
}

if (! \function_exists('Orchestra\Sidekick\phpunit_version_compare')) {
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
                str_starts_with($version, '12.2-') => '12.2.0',
                default => $version,
            }
        );

        if (\is_null($operator)) {
            return version_compare($phpunit, $version);
        }

        return version_compare($phpunit, $version, $operator);
    }
}

if (! \function_exists('Orchestra\Sidekick\php_binary')) {
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
}

if (! \function_exists('Orchestra\Sidekick\windows_os')) {
    /**
     * Determine whether the current environment is Windows based.
     *
     * @api
     *
     * @return bool
     *
     * @codeCoverageIgnore
     */
    function windows_os(): bool
    {
        return PHP_OS_FAMILY === 'Windows';
    }
}
