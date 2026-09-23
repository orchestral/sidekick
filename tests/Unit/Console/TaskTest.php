<?php

namespace Orchestra\Sidekick\Tests\Unit\Console;

use Orchestra\Sidekick\Console\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function test_it_can_dispatch_an_action_and_response(): void
    {
        $actionCalled = false;
        $response = null;

        $task = Task::action(function () use (&$actionCalled): bool {
            $actionCalled = true;

            return true;
        })->response(function (bool $result, bool $pretending) use (&$response): void {
            $response = [$result, $pretending];
        });

        $task->dispatch();

        $this->assertTrue($actionCalled);
        $this->assertSame([true, false], $response);
    }

    public function test_it_can_dispatch_a_task_when_pretending(): void
    {
        $actionCalled = false;
        $response = null;

        $task = Task::action(function () use (&$actionCalled): bool {
            $actionCalled = true;

            return false;
        })->response(function (bool $result, bool $pretending) use (&$response): void {
            $response = [$result, $pretending];
        });

        $task->dispatch(true);

        $this->assertFalse($actionCalled);
        $this->assertSame([true, true], $response);
    }

    public function test_it_does_not_dispatch_when_requirements_are_not_met(): void
    {
        $actionCalled = false;
        $responseCalled = false;

        $task = Task::action(function () use (&$actionCalled): bool {
            $actionCalled = true;

            return true;
        })->response(function () use (&$responseCalled): void {
            $responseCalled = true;
        })->requirements(function (): bool {
            return false;
        });

        $task();

        $this->assertFalse($actionCalled);
        $this->assertFalse($responseCalled);
    }

    public function test_it_can_be_invoked(): void
    {
        $response = null;

        $task = Task::action(fn (): bool => false)
            ->response(function (bool $result, bool $pretending) use (&$response): void {
                $response = [$result, $pretending];
            });

        $task(true);

        $this->assertSame([true, true], $response);
    }
}
