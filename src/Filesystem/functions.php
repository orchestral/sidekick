<?php

namespace Orchestra\Sidekick\Filesystem;

use ReflectionClass;

if (! \function_exists('Orchestra\Sidekick\Filesystem\filename_from_classname')) {
    /**
     * Resolve filename from classname.
     *
     * @api
     *
     * @param  class-string  $className
     */
    function filename_from_classname(string $className): string|false
    {
        if (! \class_exists($className, false)) {
            return false;
        }

        $reflection = new ReflectionClass($className);

        if (! is_file($classFileName = $reflection->getFileName()) && ! str_ends_with(strtolower($classFileName), '.php')) {
            return false;
        }

        return realpath($classFileName);
    }
}
