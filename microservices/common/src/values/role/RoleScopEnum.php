<?php

declare(strict_types=1);

namespace herams\common\values\role;

use Yii;

enum RoleScopEnum: string
{
    case global = 'global';
    case project = 'project';
    case workspace = 'workspace';

    public static function getEnumValue(string $enumValue): RoleScopEnum
    {
        return match ($enumValue) {
            'global' => RoleScopEnum::global,
            'project' => RoleScopEnum::project,
            'workspace' => RoleScopEnum::workspace,
            default => RoleScopEnum::global,
        };
    }

    public function label(string $locale = 'en'): string
    {
        return match ($this) {
            self::global => Yii::t('app', 'global', language: $locale),
            self::project => Yii::t('app', 'project', language: $locale),
            self::workspace => Yii::t('app', 'workspace', language: $locale),
        };
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
