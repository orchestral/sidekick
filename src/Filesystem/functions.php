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
        if (! class_exists($className, false)) {
            return false;
        }

        $classFileName = (new ReflectionClass($className))->getFileName();

        if (
            $classFileName === false
            || (! is_file($classFileName) && ! str_ends_with(strtolower($classFileName), '.php'))
        ) {
            return false;
        }

        return realpath($classFileName);
    }
}
