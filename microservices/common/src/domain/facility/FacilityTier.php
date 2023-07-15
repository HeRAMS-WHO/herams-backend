<?php

declare(strict_types=1);

namespace herams\common\domain\facility;

use Collecthor\DataInterfaces\ValueInterface;
use herams\common\interfaces\LabeledEnum;

enum FacilityTier: int implements LabeledEnum, ValueInterface
{
    case Primary = 1;
    case Secondary = 2;
    case Tertiary = 3;
    case Other = 4;
    case Unknown = 5;

    public function label(string $locale = null): string
    {
        return match ($this) {
            self::Primary => \Yii::t('app', 'Primary', language: $locale),
            self::Secondary => \Yii::t('app', 'Secondary', language: $locale),
            self::Tertiary => \Yii::t('app', 'Tertiary', language: $locale),
            self::Other => \Yii::t('app', 'Other', language: $locale),
            self::Unknown => \Yii::t('app', 'Unknown', language: $locale),
        };
    }

    public function getRawValue(): string|int|float|bool|null|array
    {
        return $this->name;
    }
}
