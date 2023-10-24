<?php

declare(strict_types=1);

namespace herams\common\models;

/**
 * Attributes
 *
 * @property int $id
 * @property int $user_id
 * @property int $role_id
 * @property string $target
 * @property int $target_id
 * @property string $created_date
 * @property int|null $created_by
 * @property string $last_modified_date
 * @property int|null $last_modified_by
 *
 */
class UserRole extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%user_role}}';
    }
}