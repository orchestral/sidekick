<?php

use function Orchestra\Sidekick\transform_relative_path;

it('can resolve relative path using `transform_relative_path()`', function () {
    expect(transform_relative_path('./TransformRelativePathTest.php', __DIR__))
        ->toBe(__FILE__);
});
