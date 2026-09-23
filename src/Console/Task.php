<?php

namespace Orchestra\Sidekick\Console;

use Closure;

class Task
{
    /**
     * Construct a new pending task.
     *
     * @param  \Closure():(bool)  $action
     * @param  (\Closure(bool, bool):(void))|null  $response
     * @param  (\Closure():(bool))|bool  $requirement
     */
    public function __construct(
        protected Closure $action,
        protected ?Closure $response = null,
        protected Closure|bool $requirement = true,
    ) {
        // ...
    }

    /**
     * Make a new pending task instance.
     *
     * @param  \Closure():(bool)  $action
     * @return static
     */
    public static function action(Closure $action)
    {
        /** @phpstan-ignore new.static */
        return new static($action);
    }

    /**
     * Set the response callback for the task.
     *
     * @param  \Closure(bool, bool):(void)  $response
     * @return $this
     */
    public function response(Closure $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Set the requirement for the task.
     *
     * @param  (\Closure():(bool))|bool  $requirement
     * @return $this
     */
    public function requirements(Closure|bool $requirement)
    {
        $this->requirement = $requirement;

        return $this;
    }

    /**
     * Handle the task.
     */
    public function dispatch(bool $pretending = false): void
    {
        if (value($this->requirement) === false) {
            return;
        }

        /** @var bool $action */
        $action = $pretending === true ? true : \call_user_func($this->action);

        /** @phpstan-ignore argument.type */
        value($this->response, $action, $pretending);
    }

    /**
     * Handle the task when invoked.
     */
    public function __invoke(bool $pretending = false): void
    {
        $this->dispatch($pretending);
    }
}
