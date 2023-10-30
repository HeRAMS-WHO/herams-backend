<?php

declare(strict_types=1);

namespace herams\common\models;

use yii\db\ActiveQuery;

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
 * Relations
 * @property User $userInfo
 * @property Role $roleInfo
 * @property Project|Workspace|null $targetInfo
 */
class UserRole extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%user_role}}';
    }

    public function getUserInfo(): ActiveQuery
    {
        return $this->hasOne(User::class, [
            'id' => 'user_id',
        ]);
    }

    public function getRoleInfo(): ActiveQuery
    {
        return $this->hasOne(Role::class, [
            'id' => 'role_id',
        ]);
    }

    public function getProjectInfo(): ActiveQuery
    {
        return $this->hasOne(Project::class, [
            'id' => 'target_id',
        ]);
    }

    public function getWorkspaceInfo(): ActiveQuery
    {
        return $this->hasOne(Workspace::class, [
            'id' => 'target_id',
        ]);
    }

    public function getCreatedByInfo(): ActiveQuery
    {
        return $this->hasOne(User::class, [
            'id' => 'created_by',
        ]);
    }

    public function getLastModifiedByInfo(): ActiveQuery
    {
        return $this->hasOne(User::class, [
            'id' => 'last_modified_by',
        ]);
    }
}
