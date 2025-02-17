<?php

use function Orchestra\Sidekick\join_paths;

it('can resolve path using `join_paths()`', function () {
    expect(realpath(__DIR__.'/JoinPathsTest.php'))
        ->toBe(join_paths(__DIR__, 'JoinPathsTest.php'));

    expect(realpath(__DIR__.'/JoinPathsTest.php'))
        ->toBe(join_paths(__DIR__, '', 'JoinPathsTest.php'));
});
