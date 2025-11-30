<?php

namespace Enumerable\Contracts;

interface Enumeration extends \Countable, \Stringable
{
    /**
     * Returns an associative array of constant names to values.
     * Uses reflection and caches the result.
     */
    public static function cases(): array;

    /**
     * Returns a list of all enum values.
     *
     * @return array<int, string|int>
     */
    public static function values(): array;

    /**
     * Creates a new enum instance from a given value.
     */
    public static function fromValue(string|int $value): static;

    /**
     * Mimics BackedEnum::from — returns enum instance.
     */
    public static function from($value): static;

    /**
     * Mimics BackedEnum::tryFrom — returns enum or null.
     *
     * @throws InvalidArgumentException
     */
    public static function tryFrom($value): ?static;
}
