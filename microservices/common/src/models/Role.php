<?php

declare(strict_types=1);

namespace herams\common\models;
/**
 * Attributes
 * @property int $id
 * @property string $name
 * @property string $scope
 * @property string $type
 * @property int|null $project_id
 * @property string $created_date
 * @property int|null $created_by
 * @property string $last_modified_date
 * @property int|null $last_modified_by
 */
class Role extends ActiveRecord {
    public static function tableName(): string {
        return '{{%role}}';
    }
}