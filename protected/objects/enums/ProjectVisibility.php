<?php
declare(strict_types=1);

namespace prime\objects\enums;

use Spatie\Enum\Enum;

/**
 * @method static self hidden()
 * @method static self public()
 * @method static self private()
 */
class ProjectVisibility extends Enum
{
    protected static function labels()
    {
        return [
            'hidden' => \Yii::t('app', 'Hidden, this project is only visible to people with permissions'),
            'public' => \Yii::t('app', 'Public, anyone can view this project'),
            'private' => \Yii::t('app', 'Private, this project is visible on the map and in the list, but people need permission to view it')
        ];
    }
}
