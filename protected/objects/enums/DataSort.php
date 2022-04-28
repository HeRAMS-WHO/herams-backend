<?php
declare(strict_types=1);

namespace prime\objects\enums;

use function iter\map;
use function iter\toArray;
use function iter\toArrayWithKeys;

enum DataSort: string
{
    case Source = "source";
    case ValueAscending = "value_ascending";
    case ValueDescending = "value_descending";
    case LabelAscending = "label_ascending";
    case LabelDescending = "label_descending";
    case CodeAscending = "code_ascending";
    case CodeDescending = "code_descending";


    private static function labels(): array {
        return [
            self::Source->value => "As defined in the survey",
            self::ValueAscending->value => "By value, ascending",
            self::ValueDescending->value => "By value, descending",
            self::LabelAscending->value => "By label, ascending",
            self::LabelDescending->value => "By label, descending",
            self::CodeAscending->value => "By code, ascending",
            self::CodeDescending->value => "By code, descending",
        ];
    }
    public function label(): string
    {
        return self::labels()[$this->value];
    }


    public static function options(): array
    {
        return self::labels();
    }

}
