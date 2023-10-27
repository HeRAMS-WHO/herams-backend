<?php

declare(strict_types=1);

namespace herams\common\values\role;

use Yii;

enum RoleScopeEnum: string
{

    case standard = 'standard';
    case custom = 'custom';

    public static function getEnumValue(string $enumValue): RoleScopeEnum
    {
        return match ($enumValue) {
            'standard' => RoleScopeEnum::standard,
            'custom' => RoleScopeEnum::custom,
            default => RoleScopeEnum::standard,
        };
    }

    public function label(string $locale = 'en'): string
    {
        return match ($this) {
            self::standard => Yii::t('app', 'standard', language: $locale),
            self::custom => Yii::t('app', 'custom', language: $locale),
        };
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
