<?php

namespace App\Traits;

use ValueError;

trait EnumHelper
{

    public static function keys(bool $replaceUnderscore = false): array
    {
        // Retrieve an array of enum names using collection mapping
        $names = collect(self::cases())->map->name->all();

        // If $replaceUnderscore is true, replace underscores with spaces in each name
        if ($replaceUnderscore) {
            $names = array_map(fn($name) => str_replace('_', ' ', $name), $names);
        }

        return $names;
    }

    public static function fromName(string $name, bool $replaceSpace = false): string
    {
        // Replace spaces with underscores if $replaceSpace is true
        $name =  $replaceSpace ?  str_replace(' ', '_', $name) : $name;

        // Iterate over enum cases to find a match based on the modified or original name
        foreach (self::cases() as $status) {
            if ($name === $status->name) {
                return $status->value;
            }
        }

        // Throw an exception if no match is found
        throw new ValueError("$name is not a valid backing value for enum " . self::class);
    }

    public static function fromValue(int $value, bool $replaceSpace = false): array
    {
        // Iterate over enum cases to find a match based on the modified or original name
        foreach (self::cases() as $status) {
            if ($value === $status->value) {
                return [
                    'id' => $status->value,
                    'name' => $status->name
                ];
            }
        }
        // Throw an exception if no match is found
        throw new ValueError("$value is not a valid backing value for enum " . self::class);
    }

    public static function selectNames(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function selectValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
