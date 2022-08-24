<?php

declare(strict_types=1);

namespace prime\objects\enums;

use prime\interfaces\LabeledEnum;

enum FacilityFunctionality: string implements LabeledEnum
{
    case Unknown = 'unknown';
    case Full = 'A1';
    case Partial = 'A2';
    case None = 'A3';

    /**
     * @codeCoverageIgnore
     */
    protected static function labels(): array
    {
        return [
            'unknown' => \Yii::t('app', 'Unknown'),
            'full' => \Yii::t('app', 'Fully functioning'),
            'partial' => \Yii::t('app', 'Partially functioning'),
            'none' => \Yii::t('app', 'Non-functioning'),
        ];
    }

    public function label(): string
    {
        return self::labels()[$this->value];
    }
}
