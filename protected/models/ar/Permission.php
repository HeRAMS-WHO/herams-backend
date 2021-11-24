<?php

declare(strict_types=1);

namespace prime\models\ar;

use JCIT\jobqueue\interfaces\JobQueueInterface;
use prime\behaviors\AuditableBehavior;
use prime\jobs\permissions\CheckImplicitAccessRequestGrantedJob;
use prime\models\ActiveRecord;
use SamIT\abac\interfaces\Grant;
use SamIT\abac\values\Authorizable;
use yii\validators\RequiredValidator;
use yii\validators\UniqueValidator;

/**
 * Class Permission
 * @package app\models
 * @property int $id
 * @property int $created_at
 * @property int $created_by
 * @property string $permission
 * @property string $source
 * @property string $source_id
 * @property string $target
 * @property string $target_id
 * @property object $sourceObject
 * @property object $targetObject
 */
class Permission extends ActiveRecord
{
    const PERMISSION_READ = 'read';
    const PERMISSION_SUMMARY = 'summary';
    const PERMISSION_WRITE = 'write';
    const PERMISSION_CREATE = 'create';
    const PERMISSION_ADMIN = 'admin';

    const PERMISSION_DEBUG_TOOLBAR = 'debug-toolbar';
    const PERMISSION_MANAGE_DASHBOARD = 'manage-dashboard';
    const PERMISSION_MANAGE_FAVORITES = 'manage-favorites';
    const PERMISSION_MANAGE_WORKSPACES = 'manage-workspaces';
    const PERMISSION_LIST_ADMIN_RESPONSES = 'list-admin-responses';
    const PERMISSION_LIST_DATA_RESPONSES = 'list-data-responses';
    const PERMISSION_LIST_FACILITIES = 'list-facilities';
    const PERMISSION_LIST_WORKSPACES = 'list-workspaces';
    const PERMISSION_CREATE_PROJECT = 'create-project';
    const PERMISSION_SURVEY_DATA = 'update-data';
    const PERMISSION_SURVEY_BACKEND = 'survey-backend';
    const PERMISSION_SHARE = 'share';
    const PERMISSION_SUPER_SHARE = 'super-share';
    const PERMISSION_DELETE = 'delete';
    const PERMISSION_EXPORT = 'export';
    const PERMISSION_RESPOND = 'respond';

    const PERMISSION_CREATE_FACILITY = 'create-facility';
    const PERMISSION_ADD_RESPONSE_TO_FACILITY = 'add-response-to-facility';

    const ROLE_LEAD = 'ROLE_LEAD';
    const ROLE_WORKSPACE_CONTRIBUTOR = 'ROLE_WORKSPACE_CONTRIBUTOR';
    const ROLE_WORKSPACE_OWNER = 'ROLE_WORKSPACE_OWNER';
    const ROLE_PROJECT_VIEWER = 'ROLE_PROJECT_VIEWER';
    const ROLE_PROJECT_OWNER = 'ROLE_PROJECT_OWNER';
    const ROLE_PROJECT_ADMIN = 'ROLE_PROJECT_ADMIN';


    const PERMISSION_DELETE_ALL_WORKSPACES = 'delete-workspaces';

    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $jobQueue = \Yii::createObject(JobQueueInterface::class);
            $jobQueue->putJob(new CheckImplicitAccessRequestGrantedJob($this->id));
        }
    }

    public function behaviors(): array
    {
        return [
            AuditableBehavior::class
        ];
    }


    public static function labels(): array
    {
        return array_merge(parent::labels(), [
            'source' => \Yii::t('app.permission', 'Source type'),
            'source_id' => \Yii::t('app.permission', 'Source ID'),
            'target' => \Yii::t('app.permission', 'Target type'),
            'target_id' => \Yii::t('app.permission', 'Target ID'),
            'permission' => \Yii::t('app.permission', 'Permission'),
            'permissionLabel' => \Yii::t('app', 'Permission')
        ]);
    }

    public function getGrant(): Grant
    {
        return new \SamIT\abac\values\Grant($this->sourceAuthorizable(), $this->targetAuthorizable(), $this->permission);
    }

    public function getPermissionLabel(): array
    {
        return $this->permissionLabels()[$this->permission];
    }

    public static function permissionLabels(): array
    {
        return [
            self::PERMISSION_READ => \Yii::t('app', 'View dashboard'),
            self::PERMISSION_WRITE => \Yii::t('app', 'Edit settings'),
            self::PERMISSION_SHARE => \Yii::t('app', 'Manage users'),
            self::PERMISSION_SUPER_SHARE => \Yii::t('app', 'Grant admin permissions'),
            self::PERMISSION_EXPORT => \Yii::t('app', 'Export data'),
            self::PERMISSION_ADMIN => \Yii::t('app', 'Allow everything'),
            self::PERMISSION_SURVEY_DATA => \Yii::t('app', 'Edit data'),
            self::PERMISSION_SURVEY_BACKEND => \Yii::t('app', 'Manage surveys'),
            self::PERMISSION_MANAGE_WORKSPACES => \Yii::t('app', 'Manage workspaces'),
            self::PERMISSION_MANAGE_DASHBOARD => \Yii::t('app', 'Configure dashboard'),
            self::PERMISSION_CREATE_PROJECT => \Yii::t('app', 'Create a new project'),
            self::PERMISSION_CREATE_FACILITY => \Yii::t('app', 'Register a new facility'),
            self::PERMISSION_DEBUG_TOOLBAR => \Yii::t('app', 'Show the debug toolbar'),

            self::ROLE_LEAD => \Yii::t('app', 'Lead'),
        ];
    }

    public function rules(): array
    {
        return [
            [['source', 'source_id', 'target', 'target_id', 'permission'], RequiredValidator::class],
            [['source', 'source_id', 'target', 'target_id', 'permission'], UniqueValidator::class,
                'targetAttribute' => ['source', 'source_id', 'target', 'target_id', 'permission']],
        ];
    }

    public static function tableName(): string
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
}
