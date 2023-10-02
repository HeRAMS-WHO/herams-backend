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
    public function getProjectInfo(): \yii\db\ActiveQuery {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }
    public function getUpdaterUserInfo(): \yii\db\ActiveQuery {
        return $this->hasOne(User::class, ['id' => 'last_modified_by']);
    }
    public function getCreatorUserInfo(): \yii\db\ActiveQuery {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }
    public function getRolePermissions(): \yii\db\ActiveQuery {
        return $this->hasMany(RolePermission::class, ['role_id' => 'id']);
    }
}