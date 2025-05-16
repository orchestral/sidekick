<?php

namespace Orchestra\Sidekick\Eloquent\Concerns;

use Orchestra\Sidekick\Eloquent\Watcher;

/**
 * Polyfill for Eloquent Model to get previous attributes.
 *
 * @see https://github.com/laravel/framework/pull/55729
 */
trait HasPreviousAttributes
{
    /**
     * The previous state of the changed model attributes.
     *
     * @var array
     */
    protected $previous = [];

    /** {@inheritdoc} */
    #[\Override]
    public function syncChanges()
    {
        parent::syncChanges();

        $this->previous = array_intersect_key($this->getRawOriginal(), $this->changes);

        return $this;
    }

    /** {@inheritdoc} */
    #[\Override]
    public function discardChanges()
    {
        $this->previous = [];

        return parent::discardChanges();
    }

    /**
     * Get the attributes that were previously original before the model was last saved.
     *
     * @return array
     */
    public function getPrevious()
    {
        return $this->previous;
    }
}
