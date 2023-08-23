<?php

declare(strict_types=1);

namespace herams\common\helpers;
use Yii;

final class CommonFieldsInTables {

    public static function convertKeysToCamelCase(array $array): array {
        $result = [];

        foreach ($array as $key => $value) {
            $words = explode('_', $key);
            $camelCaseKey = lcfirst(implode('', array_map('ucfirst', $words)));
            $result[$camelCaseKey] = $value;
        }

        return $result;
    }
    public static function forCreatingHydratation(): array {
        date_default_timezone_set('Europe/Paris');
        return self::convertKeysToCamelCase(self::forCreating());
    }
    public static function forUpdatingHydratation(): array {
        date_default_timezone_set('Europe/Paris');
        return self::convertKeysToCamelCase(self::forUpdating());
    }
    public static function forUpdating(): array {
        date_default_timezone_set('Europe/Paris');
        return [
            'last_modified_by' => Yii::$app->user->identity->id,
            'last_modified_date' => date('Y-m-d h:i:s')

        ];
    }
    public static function forCreating(): array {
        date_default_timezone_set('Europe/Paris');
        $userID = Yii::$app->user->identity->id;
        $date = date('Y-m-d h:i:s');
        return [
            'created_by' => $userID,
            'last_modified_by' => $userID,
            'created_date' => $date,
            'last_modified_date' => $date
        ];
    }
}