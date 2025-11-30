<?php

namespace Enumerable;

use ReflectionClass;
use Enumerable\Contracts\Enumeration;

/**
 * This is custom, more powerful and convenient enum class implementation.
 *
 * @property-read mixed       $value
 * @property-read string|null $label
 *
 * @package damianulan/php-enumerable
 * @author Damian Ułan <damian.ulan@protonmail.com>
 * @copyright 2025 damianulan
 */
abstract class Enum implements Enumeration
{
    protected array $attributes = [];

    /**
     * Cache of constants for each enum class.
     *
     * @var array<class-string, array<string, string|int>>
     */
    protected static array $cache = [];

    protected static array $properties = [
        'value',
        'label',
    ];

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
            $this->setAttribute('value', $value);
            $this->setAttribute('label', static::labels()[$value] ?? null);
        }
    }

    public function __isset(string $key): bool
    {
        return isset($this->attributes[$key]);
    }

    public function __unset(string $key): void
    {
        unset($this->attributes[$key]);
    }

    public function __get(string $key): mixed
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }

    public function hasAttribute(string $key): bool
    {
        return isset($this->attributes[$key]);
    }

    public function getAttribute(string $key)
    {
        if(!isset($this->attributes[$key])){
            throw new \Exception("Attribute '$key' is not defined.");
        }
        return $this->attributes[$key];
    }

    public function setAttribute(string $key, $value): void
    {
        if($value === '' && $value !== 0 && $value !== false){
            throw new \Exception('attribute cannot be empty.');
        }
        if($key === 'value'){
            if ( ! in_array($value, static::values(), true)) {
                throw new \InvalidArgumentException('Invalid enumeration value: ' . $value. ' for ' . static::class);
            }
        }
        if(!in_array($key, static::$properties)){
            throw new \InvalidArgumentException("Cannot assign a property '$key' to an enum class.");
        }
        $this->attributes[$key] = $value;
    }

    /**
     * Returns the string representation of the enum value.
     */
    public function __toString(): string
    {
        return $this->getAttribute('value') ?? '';
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

    public static function fromValue(string|int $value): static
    {
        return new static($value);
    }

    public static function from($value): static
    {
        return new static($value);
    }

    public static function tryFrom($value): ?static
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
    public function equals(Enum $enum, bool $strict_type = false): bool
    {
        return (static::class === get_class($enum) || ! $strict_type) && $this->value === $enum->value();
    }

    public function count(): int
    {
        return count(static::values());
    }
}
