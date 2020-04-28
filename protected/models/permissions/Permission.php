<?php

namespace prime\models\permissions;

use prime\models\ActiveRecord;
use SamIT\abac\interfaces\Grant;
use SamIT\abac\values\Authorizable;
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
 * @property object $sourceObject
 * @property object $targetObject
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
    const PERMISSION_SUMMARY = 'summary';
    const PERMISSION_WRITE = 'write';
    const PERMISSION_CREATE = 'create';
    const PERMISSION_ADMIN = 'admin';
    const PERMISSION_MANAGE_DASHBOARD = 'manage-dashboard';
    const PERMISSION_MANAGE_WORKSPACES = 'manage-workspaces';
    const PERMISSION_CREATE_PROJECT = 'create-project';
    const PERMISSION_LIMESURVEY = 'update-data';
    const PERMISSION_SHARE = 'share';
    const PERMISSION_SUPER_SHARE = 'super-share';
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

    public static function permissionLabels()
    {
        return [
            self::PERMISSION_READ => \Yii::t('app', 'View dashboard'),
            self::PERMISSION_WRITE => \Yii::t('app', 'Edit settings'),
            self::PERMISSION_SHARE => \Yii::t('app', 'Manage users'),
            self::PERMISSION_SUPER_SHARE => \Yii::t('app', 'Grant admin permissions'),
            self::PERMISSION_EXPORT => \Yii::t('app', 'Download data'),
            self::PERMISSION_ADMIN => \Yii::t('app', 'Allow everything'),
            self::PERMISSION_LIMESURVEY => \Yii::t('app', 'Edit data'),
            self::PERMISSION_MANAGE_WORKSPACES => \Yii::t('app', 'Manage workspaces'),
            self::PERMISSION_MANAGE_DASHBOARD => \Yii::t('app', 'Configure dashboard'),
            self::PERMISSION_CREATE_PROJECT => \Yii::t('app', 'Create a new project'),
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

    public function sourceAuthorizable(): Authorizable
    {
        return new Authorizable($this->source_id, $this->source);
    }

    public function targetAuthorizable(): Authorizable
    {
        return new Authorizable($this->target_id, $this->target);
    }

    public function getGrant(): Grant
    {
        return new \SamIT\abac\values\Grant($this->sourceAuthorizable(), $this->targetAuthorizable(), $this->permission);
    }
}
