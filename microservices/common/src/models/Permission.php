<?php

declare(strict_types=1);

namespace herams\common\models;

/**
 * Attributes
 * @property string $code
 * @property string $name
 * @property string $parent
 * @property string $created_date
 * @property int|null $created_by
 * @property string $last_modified_date
 * @property int|null $last_modified_by
 */
class Permission extends ActiveRecord
{
    const GLOBAL_TARGET = 'global';
    const PROJECT_TARGET = 'project';
    const WORKSPACE_TARGET = 'workspace';

    public static function tableName(): string
    {
        return '{{%permissions}}';
    }

    public static function primaryKey(): array
    {
        return ['code'];
    }
}
