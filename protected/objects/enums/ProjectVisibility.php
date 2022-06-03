<?php

declare(strict_types=1);

namespace prime\objects\enums;

/**
 * @method static self hidden()
 * @method static self public()
 * @method static self private()
 */
enum ProjectVisibility: string
{

    case Hidden = 'hidden';
    case Public = 'public';
    case Private = 'private';

    public function label(): string {
        return match($this) {
            self::Hidden => \Yii::t('app', 'Hidden, this project is only visible to people with permissions'),
            self::Public => \Yii::t('app', 'Public, anyone can view this project'),
            self::Private => \Yii::t('app', 'Private, this project is visible on the map and in the list, but people need permission to view it')
        };
    }

    public static function toArray(): array
    {
        $result = [];
        foreach(self::cases() as $projectStatus) {
            $result[$projectStatus->value] = $projectStatus->label();
        }
        return $result;
    }
}
