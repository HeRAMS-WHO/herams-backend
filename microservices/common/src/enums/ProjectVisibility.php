<?php

declare(strict_types=1);

namespace herams\common\enums;

enum ProjectVisibility: string implements \JsonSerializable
{
    case Hidden = 'hidden';
    case Public = 'public';
    case Private = 'private';

    public function label(): string
    {
        return match ($this) {
            self::Hidden => \Yii::t('app', 'Hidden, this project is only visible to people with permissions'),
            self::Public => \Yii::t('app', 'Public, anyone can view this project'),
            self::Private => \Yii::t('app', 'Private, this project is visible on the map and in the list, but people need permission to view it')
        };
    }
    public static function getValueFromText(self $value): ?string
    {

        foreach (self::cases() as $case) {
            if ($case->value == $value->value) {
                return $case->label();
            }
        }

        return null; // El texto no coincide con ningún valor en el enum
    }
    public static function toArray(): array
    {
        return array_map(fn($case) => [
            'label' => $case->label(),
            'value' => $case->value
        ], self::cases());
    }

    public function jsonSerialize(): mixed
    {
        return $this->value;
    }

}
