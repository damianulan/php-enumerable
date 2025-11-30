<?php

namespace Enumerable;

use Enumerable\Support\EnumCollection;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * This is custom, more powerful and convenient enum class implementation in Laravel.
 *
 * @property-read mixed       $value
 * @property-read string|null $label
 *
 * @package damianulan/php-enumerable
 * @author Damian Ułan <damian.ulan@protonmail.com>
 * @copyright 2025 damianulan
 */
abstract class LaraEnum extends Enum implements CastsAttributes
{
    /**
     * Returns cases() result as Laravel Collection.
     *
     * @return \Enumerable\Support\EnumCollection
     */
    public static function collection(): EnumCollection
    {
        $cases = static::cases();
        return EnumCollection::make($cases);
    }

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return empty($value) ? null : self::tryFrom($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string|int
    {
        if ($value instanceof Enum && isset($value->value)) {
            return $value->value();
        }

        return $value;
    }
}
