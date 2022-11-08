<?php

declare(strict_types=1);

namespace herams\common\domain\facility;

use Collecthor\DataInterfaces\ValueInterface;
use herams\common\interfaces\LabeledEnum;

enum FacilityTier: string implements LabeledEnum, ValueInterface
{
    case Primary = "primary";
    case Secondary = "secondary";
    case Tertiary = "tertiary";
    case Unknown = "unknown";

    public function label(string $locale = null): string
    {
        return match ($this) {
            self::Primary => \Yii::t('app', 'Primary', language: $locale),
            self::Secondary => \Yii::t('app', 'Secondary', language: $locale),
            self::Tertiary => \Yii::t('app', 'Tertiary', language: $locale),
            self::Unknown => \Yii::t('app', 'Unknown', language: $locale),
        };
    }

    public function getRawValue(): string|int|float|bool|null|array
    {
        return $this->name;
    }
}
