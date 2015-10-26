<?php

namespace prime\models\permissions;

use prime\components\ActiveRecord;
use prime\models\Project;
use prime\models\User;

/**
 * Class Permission
 * @package app\models
 * @property string $permission
 * @property string $source
 * @property int $source_id
 * @property string $target
 * @property int $target_id
 */
class Permission extends ActiveRecord
{
    const PERMISSION_READ = 'read';
    const PERMISSION_WRITE = 'write';
    const PERMISSION_SHARE = 'share';

    public function getPermissionLabel()
    {
        return $this->permissionLabels()[$this->permission];
    }

    /*
     * @todo fix for greedy loading
     */
    public function getSourceObject()
    {
        return $this->hasOne($this->source, ['id' => 'source_id']);
    }

    /*
     * @todo fix for greedy loading
     */
    public function getTargetObject()
    {
        return $this->hasOne($this->target, ['id' => 'target_id']);
    }

    public static function grand(ActiveRecord $source,ActiveRecord $target, $permission, $strict = false)
    {
        if($source->isNewRecord) {
            throw new \Exception('Source is new record');
        }

        if($target->isNewRecord) {
            throw new \Exception('Target is new record');
        }

        $p = new Permission([
            'source' => get_class($source),
            'source_id' => $source->id,
            'target' => get_class($target),
            'target_id' => $target->id
        ]);
        $p = $p->loadFromAttributeData();

        if($p->isNewRecord || $strict || static::permissionLevels()[$permission] > $p->permission) {
            $p->permission = $permission;
            $p->save();
        }

        return $p;
    }

    public static function instantiate($row)
    {
        if($row['source'] == User::class && $row['target'] == Project::class) {
            return new UserProject();
        }
        return parent::instantiate($row);
    }


    public static function isAllowed($sources, ActiveRecord $target, $permission)
    {
        $permissionLevel = self::permissionLevels()[$permission];
        $permissions = array_keys(array_filter(self::permissionLevels(), function($value) use ($permissionLevel) {
            return $value >= $permissionLevel;
        }));

        $query = self::find();

        foreach(is_array($sources) ? $sources : [$sources] as $source) {
            $query->orWhere(['source' => get_class($source),
                'source_id' => $source->id]);
        }

        $query->andWhere(['target' => get_class($target), 'target_id' => $target->id, 'permission' => $permissions]);

        return app()->db->cache(function($db) use ($query) {
            return $query->exists();
        });
    }

    public static function permissionLabels()
    {
        return [
            self::PERMISSION_READ => 'Read',
            self::PERMISSION_WRITE => 'Write',
            self::PERMISSION_SHARE => 'Share'
        ];
    }

    public static function permissionLevels()
    {
        return [
            self::PERMISSION_READ => 0,
            self::PERMISSION_WRITE => 1,
            self::PERMISSION_SHARE => 2,
        ];
    }

    public function rules()
    {
        return [
            [['source', 'source_id', 'target', 'target_id', 'permission'], 'required'],
            [['source', 'source_id', 'target', 'target_id'], 'unique', 'targetAttribute' => ['source', 'source_id', 'target', 'target_id']],
            [['permission'], 'in', 'range' => array_keys(self::permissionLevels())]
        ];
    }

    public static function tableName()
    {
        return '{{%permission}}';
    }


}