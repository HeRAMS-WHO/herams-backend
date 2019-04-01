<?php

namespace prime\models\permissions;

use prime\interfaces\AuthorizableInterface;
use prime\models\ActiveRecord;
use prime\models\ar\Workspace;
use prime\models\ar\User;
use yii\db\ActiveRecordInterface;
use yii\validators\RequiredValidator;
use yii\validators\UniqueValidator;

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
    // Cache for the results for the anyAllowed lookup.
    private static $anyCache = [];
    // Cache for the results for the isAllowed loookup.
    private static $cache = [];

    const PERMISSION_READ = 'read';
    const PERMISSION_WRITE = 'write';
    const PERMISSION_SHARE = 'share';
    const PERMISSION_ADMIN = 'admin';
    const PERMISSION_INSTANTIATE = 'instantiate';


    public static function loadCache($sourceModel, $sourceId)
    {
        if (!empty(self::$cache)) {
            return;
        }
        /** @var self $permission */
        foreach(self::findAll([
            'source' => $sourceModel,
            'source_id' => $sourceId
        ]) as $permission) {
            self::setCache($permission->source, $permission->source_id, $permission->target, $permission->target_id, $permission->permission, true);
        }
        // Indicator that tells us this source has been preloaded.
        self::$cache[$sourceModel . $sourceId] = true;
    }
    public function attributeLabels()
    {
        return [
            'permissionLabel' => \Yii::t('app', 'Permission')
        ];
    }

    /**
     * Key is the checked permission, values are the permissions by which it is implied.
     * @return array
     */
    public static function impliedPermissions() {
        return [
            'read' => ['write', 'admin'],
            'write' => ['admin']
        ];
    }

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

    public static function grant(\yii\db\ActiveRecord $source,\yii\db\ActiveRecord $target, $permission)
    {
        if($source->isNewRecord) {
            throw new \Exception('Source is new record');
        }

        if($target->isNewRecord) {
            throw new \Exception('Target is new record');
        }

        $p = new Permission([
            'source' => $source instanceof AuthorizableInterface ? $source->getAuthName() :get_class($source),
            'source_id' => $source->id,
            'target' => $target instanceof AuthorizableInterface ? $target->getAuthName() : get_class($target),
            'target_id' => $target->id,
            'permission' => $permission
        ]);
        $p = $p->loadFromAttributeData();

        return $p->save();
    }

    public static function instantiate($row)
    {
        if($row['source'] == User::class && $row['target'] == Workspace::class) {
            return new UserProject();
        }
        return parent::instantiate($row);
    }

    /**
     * Checks if a set of sources is allowed $permission on the $target.
     * @param ActiveRecordInterface $source The source object.
     * @param ActiveRecordInterface $target The target objects.
     * @param $permission The permission to be checked.
     * @return boolean
     * @throws \Exception
     */
    public static function isAllowed(ActiveRecordInterface $source, ActiveRecordInterface $target, $permission)
    {
        if ($target->primaryKey === null) {
            throw new \Exception("Invalid record.");
        }
        return self::isAllowedById(get_class($source), $source->getPrimaryKey(), get_class($target), $target->getPrimaryKey(), $permission);
    }


    private static function getCache(
        $sourceModel,
        $sourceId,
        $targetModel,
        $targetId,
        $permission
    ): bool {
        if (!isset($targetId)) {
            throw new \Exception('targetId is required');
        }
        \Yii::info("Checking from cache: $sourceModel [$sourceId] --> $targetModel [$targetId]");
        $key = md5($sourceModel . $sourceId . $targetModel . $targetId . $permission);

        return self::$cache[$key] ?? false;
    }

    private static function setCache($sourceModel, $sourceId, $targetModel, $targetId, $permission, $value)
    {
        $key = md5($sourceModel . $sourceId . $targetModel . $targetId . $permission);
        self::$cache[$key] = $value;
    }


    public static function isAllowedById($sourceModel, $sourceId, $targetModel, $targetId, $permission)
    {
        self::loadCache($sourceModel, $sourceId);

        // Check cache
        if (false === $result = self::getCache($sourceModel, $sourceId, $targetModel, $targetId, $permission)) {
            // Cache does not have an entry.
            // Check implied.
            foreach(static::impliedPermissions()[$permission] ?? [] as $explicitPermission) {
                // Use recursion.
                if(self::isAllowedById($sourceModel, $sourceId, $targetModel, $targetId, $explicitPermission)) {
                    return true;
                }
            }
        }

        return $result;
    }


    /**
     * Checks if a $source is allowed $permission on any $targetClass instance.
     * @param ActiveRecordInterface $source
     * @param string $targetClass
     * @param string $permission
     */
    public static function anyAllowed(ActiveRecordInterface $source, $targetModel, $permission)
    {
        $query = self::find();
        $query->andWhere(['source' => get_class($source), 'source_id' => $source->id]);
        $query->andWhere(['target' => $targetModel, 'permission' => $permission]);

        return self::getDb()->cache(function($db) use ($query) {
            return $query->exists();
        }, 120);
    }

    public static function anyAllowedById($sourceModel, $sourceId, $targetModel, $permission)
    {
        $query = self::find();
        $query->andWhere(['source' => $sourceModel, 'source_id' => $sourceId]);
        $query->andWhere(['target' => $targetModel, 'permission' => $permission]);

        return self::getDb()->cache(function($db) use ($query) {
            return $query->exists();
        }, 120);
    }

    public static function permissionLabels()
    {
        return [
            self::PERMISSION_READ => \Yii::t('app', 'Read, this grants access to the dashboard'),
            self::PERMISSION_WRITE => \Yii::t('app', 'Write, this grants access to update the settings'),
            self::PERMISSION_SHARE => \Yii::t('app', 'Share, this allows sharing permissions, only makes sense when combined with other permissions'),
            self::PERMISSION_INSTANTIATE => \Yii::t('app', 'Administer workspaces'),
            self::PERMISSION_ADMIN => \Yii::t('app', 'Full access'),
        ];
    }

    public static function permissionLevels()
    {
        return [
            self::PERMISSION_READ => 0,
            self::PERMISSION_INSTANTIATE => 1,
            self::PERMISSION_WRITE => 2,
            self::PERMISSION_SHARE => 3,
            self::PERMISSION_ADMIN => 4,
        ];
    }

    public function rules()
    {
        return [
            [['source', 'source_id', 'target', 'target_id', 'permission'], RequiredValidator::class],
            [['source', 'source_id', 'target', 'target_id', 'permission'], UniqueValidator::class,
                'targetAttribute' => ['source', 'source_id', 'target', 'target_id', 'permission']],
            [['permission'], 'in', 'range' => array_keys(self::permissionLabels())]
        ];
    }

    public static function tableName()
    {
        return '{{%permission}}';
    }


}