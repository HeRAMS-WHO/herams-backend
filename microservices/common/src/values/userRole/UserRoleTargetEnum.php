<?php

declare(strict_types=1);

namespace herams\common\values\userRole;

use Yii;

enum UserRoleTargetEnum: string
{
    case global = 'global';
    case project = 'project';
    case workspace = 'workspace';

    public static function getEnumValue(string $enumValue): UserRoleTargetEnum
    {
        return match ($enumValue) {
            'global' => UserRoleTargetEnum::global,
            'project' => UserRoleTargetEnum::project,
            'workspace' => UserRoleTargetEnum::workspace,
            default => UserRoleTargetEnum::global,
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
