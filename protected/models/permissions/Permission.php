<?php

namespace prime\models\permissions;

use prime\models\ActiveRecord;
use prime\models\ar\User;
use prime\models\ar\Workspace;
use app\queries\PermissionQuery;
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
 *
 */
class Permission extends ActiveRecord
{
    // If set to false we will reload the cache every time.
    public static $enableCaching = true;

    // Cache for the results for the anyAllowed lookup.
    private static $anyCache = [];
    // Cache for the results for the isAllowed loookup.
    private static $cache = [];

    const PERMISSION_READ = 'read';
    const PERMISSION_WRITE = 'write';
    const PERMISSION_CREATE = 'create';
    const PERMISSION_ADMIN = 'admin';
    const PERMISSION_CREATE_PAGE = 'create-page';
    const PERMISSION_MANAGE_WORKSPACES = 'manage-workspaces';
    const PERMISSION_CREATE_PROJECT = 'create-page';
    const PERMISSION_LIMESURVEY = 'update-data';
    const PERMISSION_SHARE = 'share';
    const PERMISSION_UNSHARE = 'unshare';
    const PERMISSION_DELETE = 'delete';
    const PERMISSION_EXPORT = 'export';

    const ROLE_WORKSPACE_CONTRIBUTOR = 'ROLE_WORKSPACE_CONTRIBUTOR';
    const ROLE_WORKSPACE_OWNER = 'ROLE_WORKSPACE_OWNER';
    const ROLE_PROJECT_VIEWER = 'ROLE_PROJECT_VIEWER';
    const ROLE_PROJECT_OWNER = 'ROLE_PROJECT_OWNER';
    const ROLE_PROJECT_ADMIN = 'ROLE_PROJECT_ADMIN';


    public function attributeLabels()
    {
        return [
            'permissionLabel' => \Yii::t('app', 'Permission')
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

    public static function instantiate($row)
    {
        if($row['source'] == User::class && $row['target'] == Workspace::class) {
            return new UserProject();
        }
        return parent::instantiate($row);
    }

    public static function permissionLabels()
    {
        return [
            self::PERMISSION_READ => \Yii::t('app', 'Read, this grants access to the dashboard'),
            self::PERMISSION_WRITE => \Yii::t('app', 'Write, this grants access to properties'),
            self::PERMISSION_SHARE => \Yii::t('app', 'Share, allows this users to give other users limited access'),
            self::PERMISSION_EXPORT => \Yii::t('app', 'Export, allows exporting data'),
            self::PERMISSION_ADMIN => \Yii::t('app', 'Allow everything'),
            self::PERMISSION_LIMESURVEY => \Yii::t('app', 'Manage data in LimeSurvey'),
            self::PERMISSION_MANAGE_WORKSPACES => \Yii::t('app', 'Manage workspaces in the project')
        ];
    }

    public function rules()
    {
        return [
            [['source', 'source_id', 'target', 'target_id', 'permission'], RequiredValidator::class],
            [['source', 'source_id', 'target', 'target_id', 'permission'], UniqueValidator::class,
                'targetAttribute' => ['source', 'source_id', 'target', 'target_id', 'permission']],
        ];
    }

    public static function tableName()
    {
        return '{{%permission}}';
    }


}