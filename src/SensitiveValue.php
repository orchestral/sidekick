<?php

namespace Orchestra\Sidekick;

/**
 * @api
 */
final class SensitiveValue implements JsonSerializable
{
    /**
     * Construct a new sensitive value.
     */
    public function __construct(
        private readonly mixed $value
    ) {
        //
    }

    /**
     * Get the original value.
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Transform the value for debugging.
     */
    public function __debugInfo(): array
    {
        return [];
    }

    /**
     * Get the value for serialization.
     */
    public function jsonSerializable(): string
    {
        return '******';
    }
}
