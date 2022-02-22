<?php

declare(strict_types=1);

namespace prime\objects\enums;

/**
 * @method static self unknown()
 * @method static self full()
 * @method static self partial()
 * @method static self none()
 */
class FacilityFunctionality extends Enum
{
    /**
     * @codeCoverageIgnore
     */
    protected static function values(): array
    {
        return [
            'unknown' => 'unknown',
            'full' => 'A1',
            'partial' => 'A2',
            'none' => 'A3',
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    protected static function labels(): array
    {
        return [
            'unknown' => \Yii::t('app', 'Unknown'),
            'full' => \Yii::t('app', 'Fully functioning'),
            'partial' => \Yii::t('app', 'Partially functioning'),
            'none' => \Yii::t('app', 'Non-functioning')
        ];
    }
}
