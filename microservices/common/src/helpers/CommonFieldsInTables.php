<?php

declare(strict_types=1);

namespace herams\common\helpers;

use Yii;

final class CommonFieldsInTables
{
    public static function convertKeysToCamelCase(array $array): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $words = explode('_', $key);
            $camelCaseKey = lcfirst(implode('', array_map('ucfirst', $words)));
            $result[$camelCaseKey] = $value;
        }

        return $result;
    }

    public static function forCreatingHydratation(): array
    {
        return self::convertKeysToCamelCase(self::forCreating());
    }

    public static function forUpdatingHydratation(): array
    {
        return self::convertKeysToCamelCase(self::forUpdating());
    }

    public static function forUpdating(): array
    {
        $timezone = new \DateTimeZone('UTC');
        $currentDateTime = new \DateTime('now', $timezone);
        $currentDateTime->modify('+1 hour');
        $currentTimeUTCPlus1 = $currentDateTime->format('Y-m-d H:i:s');
        return [
            'last_modified_by' => Yii::$app->user->identity->id,
            'last_modified_date' => $currentTimeUTCPlus1,

        ];
    }

    public static function forCreating(): array
    {
        date_default_timezone_set('Europe/Paris');
        $userID = Yii::$app->user->identity->id;
        $timezone = new \DateTimeZone('UTC');
        $currentDateTime = new \DateTime('now', $timezone);
        $currentDateTime->modify('+1 hour');
        $date = $currentDateTime->format('Y-m-d H:i:s');
        return [
            'created_by' => $userID,
            'last_modified_by' => $userID,
            'created_date' => $date,
            'last_modified_date' => $date,
        ];
    }
}
