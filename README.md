# PHP Enumerable

[![Static Badge](https://img.shields.io/badge/made_with-Laravel-red?style=for-the-badge)](https://laravel.com/docs/11.x/releases) &nbsp; [![Licence](https://img.shields.io/github/license/Ileriayo/markdown-badges?style=for-the-badge)](./LICENSE) &nbsp; [![Static Badge](https://img.shields.io/badge/maintainer-damianulan-blue?style=for-the-badge)](https://damianulan.me)

## Description
This package provides extensive enumeration support for PHP and Laravel projects, as a substitute for PHP built-in enums.

## Installation

You can install the package via composer in your laravel project:

```
composer require damianulan/php-enumerable
```

The package will automatically register itself.

## Usage

Use `Enum` class to create enumeration in plain php, in laravel project use `LaraEnum` class, which provides additional casting support.
Class `Enum` instances mimic implementation of [BackedEnum](https://www.php.net/manual/en/class.backedenum.php) and [UnitEnum](https://www.php.net/manual/en/class.unitenum.php).

```php
use Enumerable\Enum;

class CampaignStage extends Enum
{
    public const PENDING = 'pending';

    public const IN_PROGRESS = 'in_progress';

    public const COMPLETED = 'completed';
}

```

Check the examples below:
```php
StageEnum::cases(); // returns an assoc array of all enum cases with enum instance
StageEnum::values(); // returns an array of all enum values
StageEnum::labels(); // should return an assoc array of all enum cases with human-readable label. This method should be declared in Enum child class. Accessible by attribue `label`.
// ['enum_value' => 'Human-readable label']

$stage = StageEnum::fromValue('pending'); // returns enum instance
$stage->label; // returns human-readable label if labels() method is declared in child class
$stage->value; // returns enum value

StageEnum::Pending; // returns enum value, not its instance
```

In Laravel assign your `LaraEnum` to yout model's `casts` property:
```php
protected $casts = array(
    'stage' => StageEnum::class,
);
```
It will return `StageEnum` instance instead of string when accessing model's `stage` property.

## More examples
```php
$stage->is('pending'); // returns true if enum value is equal to given string
$stage->isNot('pending'); // returns true if enum value is not equal to given string
$stage->equals($otherEnum); // returns true if enum value is equal to given enum instance
```

## Contact & Contributing

Any question You can submit to **damian.ulan@protonmail.com**.
