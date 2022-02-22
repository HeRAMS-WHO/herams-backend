<?php

declare(strict_types=1);

namespace prime\objects\enums;

/**
 * @method static self limesurvey()
 * @method static self surveyJs()
 */
class ProjectType extends Enum
{
    /**
     * @codeCoverageIgnore
     */
    protected static function values(): array
    {
        return [
            'limesurvey' => 'limesurvey',
            'surveyJs' => 'surveyJs',
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    protected static function labels(): array
    {
        return [
            'limesurvey' => \Yii::t('app', 'Limesurvey'),
            'surveyJs' => \Yii::t('app', 'SurveyJS'),
        ];
    }
}
