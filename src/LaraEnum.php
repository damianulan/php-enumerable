<?php

namespace Enumerable;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * This is custom, more powerful and convenient enum class implementation.
 *
 * @package damianulan/php-enumerable
 * @author Damian Ułan <damian.ulan@protonmail.com>
 * @copyright 2025 damianulan
 */
abstract class LaraEnum extends Enum implements CastsAttributes
{
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
