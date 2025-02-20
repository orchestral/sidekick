<?php

use function Orchestra\Sidekick\laravel_version_compare;
use function Orchestra\Sidekick\phpunit_version_compare;
use function PHPStan\Testing\assertType;

assertType('bool', laravel_version_compare('7.0.0', '>='));
assertType('int', laravel_version_compare('7.0.0'));

assertType('bool', phpunit_version_compare('9.0.0', '>='));
assertType('int', phpunit_version_compare('9.0.0'));
