<?php

declare(strict_types=1);

namespace herams\common\domain\facility;

use Collecthor\DataInterfaces\ValueInterface;
use herams\common\interfaces\LabeledEnum;

enum HSDUStateEnum: int implements LabeledEnum, ValueInterface
{
    case acceptUpdates = 1;
    case notAcceptUpdates = 0;
    case Unknown = 2;

    public function label(string $locale = null): string
    {
        return match ($this) {
            self::acceptUpdates => \Yii::t('app', 'Accept updates', language: $locale),
            self::notAcceptUpdates => \Yii::t('app', 'Do not accept updates', language: $locale),
            self::Unknown => \Yii::t('app', 'Unknown', language: $locale),
        };
    }

    public function getRawValue(): string|int|float|bool|null|array
    {
        return $this->name;
    }
}
