<?php
declare(strict_types=1);

namespace prime\objects\enums;

/**
 * @method static self ongoing()
 * @method static self baseline()
 * @method static self target()
 * @method static self emergency()
 */
class ProjectStatus extends Enum
{
    protected static function values(): array
    {
        return [
            'ongoing' => 0,
            'baseline' => 1,
            'target' => 2,
            'emergency' => 3
        ];
    }

    protected static function labels(): array
    {
        return [
            'ongoing' => \Yii::t('app', 'Ongoing'),
            'baseline' => \Yii::t('app', 'Baseline'),
            'target' => \Yii::t('app', 'Target'),
            'emergency' => \Yii::t('app', 'Emergency specific')
        ];
    }
}
