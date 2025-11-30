<?php

namespace Enumerable;

use ReflectionClass;

/**
 * This is custom, more powerful and convenient enum class implementation.
 *
 * @package damianulan/php-enumerable
 * @author Damian Ułan <damian.ulan@protonmail.com>
 * @copyright 2025 damianulan
 */
abstract class Enum implements \UnitEnum, \BackedEnum
{
    /**
     * The actual value of the enum instance.
     */
    public string|int $value;

    /**
     * Cache of constants for each enum class.
     *
     * @var array<class-string, array<string, string|int>>
     */
    protected static array $cache = array();

    /**
     * Enum constructor. Validates that the value exists in the enum.
     *
     * @param  string|int  $value
     *
     * @throws InvalidArgumentException if the value is not valid for the enum.
     */
    public function __construct($value = null)
    {
        if ( ! is_null($value)) {
            if ( ! in_array($value, static::values(), true)) {
                throw new \InvalidArgumentException('Invalid enum value: ' . $value);
            }

            $this->value = $value;
        }
    }

    /**
     * Returns the string representation of the enum value.
     */
    public function __toString(): string
    {
        return $this->value ?? '';
    }

    /**
     * Returns a list of all enum values.
     *
     * @return array<int, string|int>
     */
    public static function values(): array
    {
        $class = static::class;
        $reflection = new ReflectionClass($class);

        return $reflection->getConstants();
    }

    /**
     * Returns a map of enum values to human-readable labels.
     * Should be overridden by child classes.
     *
     * @return array<string|int, string>
     */
    public static function labels(): array
    {
        return array();
    }

    /**
     * Returns an associative array of constant names to values.
     * Uses reflection and caches the result.
     */
    public static function cases(): array
    {
        $class = static::class;
        $collection = [];
        if ( ! isset(self::$cache[$class])) {
            $reflection = new ReflectionClass($class);
            foreach ($reflection->getConstants() as $key => $value) {
                $collection[$key] = self::tryFrom($value);
            }

            self::$cache[$class] = $collection;
        }

        $fromCache = self::$cache[$class] ?? null;
        if ( ! is_null($fromCache) && is_array($fromCache)) {
            return $fromCache;
        }

        return $collection;
    }

    /**
     * Creates a new enum instance from a given value.
     */
    public static function fromValue(string|int $value): static
    {
        return new static($value);
    }

    /**
     * Mimics BackedEnum::from — returns enum or throws.
     *
     * @throws InvalidArgumentException
     */
    public static function from(string|int $value): static
    {
        return new static($value);
    }

    /**
     * Mimics BackedEnum::tryFrom — returns enum or null.
     */
    public static function tryFrom(string|int $value): ?static
    {
        try {
            return new static($value);
        } catch (\InvalidArgumentException) {
            return null;
        }
    }

    /**
     * Returns the raw enum value.
     */
    public function value(): string|int
    {
        return $this->value;
    }

    /**
     * Returns the human-readable label for the enum value.
     */
    public function label(): string
    {
        return static::labels()[$this->value] ?? (string) $this->value;
    }

    /**
     * Check if given string value equals enum value.
     */
    public function is(string $value): bool
    {
        return $value === $this->value();
    }

    /**
     * Check if given string value does not equal enum value.
     */
    public function isNot(string $value): bool
    {
        return $value === $this->value();
    }

    /**
     * Compares this enum with another for equality.
     */
    public function equals(Enum $enum): bool
    {
        return static::class === get_class($enum) && $this->value === $enum->value();
    }

}
