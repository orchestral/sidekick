<?php

namespace Orchestra\Sidekick\Tests\Unit\Filesystem\Functions;

use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\Filesystem\filename_from_classname;
use function Orchestra\Sidekick\join_paths;

class FilenameFromClassnameTest extends TestCase
{
    /**
     * The `orchestra/sidekick` directory.
     */
    protected string $projectDirectory;

    /** {@inheritDoc} */
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->projectDirectory = join_paths(
            realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..')
        );
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->projectDirectory);
    }

    public function test_it_can_resolve_classes()
    {
        $this->assertSame(
            join_paths($this->projectDirectory, 'src', 'UndefinedValue.php'),
            filename_from_classname('Orchestra\Sidekick\UndefinedValue')
        );
    }
}
