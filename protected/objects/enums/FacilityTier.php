<?php

declare(strict_types=1);

namespace prime\objects\enums;

use prime\interfaces\LabeledEnum;

enum FacilityTier: string implements LabeledEnum
{
    case Primary = "primary";
    case Secondary = "secondary";
    case Tertiary = "tertiary";
    case Unknown = "unknown";

    public function label(): string
    {
        return match($this) {
            self::Primary => \Yii::t('app', 'Primary'),
            self::Secondary => \Yii::t('app', 'Secondary'),
            self::Tertiary => \Yii::t('app', 'Tertiary'),
            self::Unknown => \Yii::t('app', 'Unknown'),
        };
    }
}
