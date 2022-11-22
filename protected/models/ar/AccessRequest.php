<?php

declare(strict_types=1);

namespace prime\models\ar;

use Carbon\Carbon;
use herams\common\attributes\Audits;
use herams\common\attributes\TriggersJob;
use herams\common\domain\user\User;
use herams\common\jobs\accessRequests\CreatedNotificationJob;
use herams\common\models\ActiveRecord;
use herams\common\models\Permission;
use herams\common\models\Project;
use herams\common\models\Workspace;
use herams\common\queries\ActiveQuery;
use prime\queries\AccessRequestQuery;
use SamIT\Yii2\VirtualFields\VirtualFieldBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use yii\validators\ExistValidator;
use yii\validators\InlineValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use function iter\keys;

/**
 * Class AccessRequest
 * @package prime\models\ar
 *
 * Attributes
 * @property bool $accepted
 * @property string $body
 * @property string $created_at
 * @property int $created_by
 * @property string $expires_at
 * @property int $id
 * @property array $permissions
 * @property string $responded_at
 * @property int $responded_by
 * @property string $response
 * @property string $subject
 * @property string $target_class
 * @property int $target_id
 *
 * @property User $createdByUser
 * @property Project|Workspace $target
 */
#[
    Audits(self::EVENT_AFTER_INSERT),
    Audits(self::EVENT_AFTER_DELETE),
    Audits(self::EVENT_AFTER_UPDATE),
    TriggersJob(CreatedNotificationJob::class)
]
class AccessRequest extends ActiveRecord
{
    public const PERMISSION_CONTRIBUTE = 'contribute';

    public const PERMISSION_EXPORT = 'export';

    public const PERMISSION_OTHER = 'other';

    public const PERMISSION_READ = 'read';

    public const PERMISSION_WRITE = 'write';

    private static function defaultValues(): array
    {
        return [
            'expires_at' => Carbon::now()->addWeek(2),
        ];
    }

    private function initDefaults(): void
    {
        $defaults = self::defaultValues();
        $this->setAttributes($defaults, false);
    }

    public function __construct($config = [])
    {
        $this->initDefaults();
        parent::__construct($config);
    }

    public static function populateRecord($record, $row): void
    {
        // Need to deal with default values here, they must be unset if not being loaded from the database.
        foreach (keys(self::defaultValues()) as $attribute) {
            if (! isset($row[$attribute])) {
                $record->setAttribute($attribute, null);
            }
        }
        parent::populateRecord($record, $row);
    }

    public static function labels(): array
    {
        return parent::labels() + [
            'accepted' => \Yii::t('app.model.accessRequest', 'Accepted'),
            'body' => \Yii::t('app.model.accessRequest', 'Body'),
            'expires_at' => \Yii::t('app.model.accessRequest', 'Expires at'),
            'permissions' => \Yii::t('app.model.accessRequest', 'Subject'),
            'responded_at' => \Yii::t('app.model.accessRequest', 'Responded at'),
            'responded_by' => \Yii::t('app.model.accessRequest', 'Responded by'),
            'response' => \Yii::t('app.model.accessRequest', 'Response'),
            'subject' => \Yii::t('app.model.accessRequest', 'Subject'),
            'target_class' => \Yii::t('app.model.accessRequest', 'Target class'),
            'target_id' => \Yii::t('app.model.accessRequest', 'Target'),
        ];
    }

    private static function virtualFields(): array
    {
        return [
            'created_at' => [
                VirtualFieldBehavior::GREEDY => Audit::find()->limit(1)->select('max([[created_at]])')
                    ->created()
                    ->forModelClass(static::class)
                    ->forSubjectId(new Expression(self::tableName() . '.[[id]]')),
                VirtualFieldBehavior::LAZY => static fn (self $model): ?string
                => Audit::find()->forModel($model)->created()->select('max([[created_at]])')->scalar(),
            ],
        ];
    }

    public function behaviors(): array
    {
        return [
            BlameableBehavior::class => [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
            ],
            VirtualFieldBehavior::class => [
                'class' => VirtualFieldBehavior::class,
                'virtualFields' => self::virtualFields(),
            ],
        ];
    }

    public static function find(): AccessRequestQuery
    {
        return \Yii::createObject(AccessRequestQuery::class, [get_called_class()]);
    }

    public function getCreatedByUser(): ActiveQuery
    {
        return $this->hasOne(User::class, [
            'id' => 'created_by',
        ]);
    }

    public function getRespondedByUser(): ActiveQuery
    {
        return $this->hasOne(User::class, [
            'id' => 'responded_by',
        ]);
    }

    public function getTarget(): ActiveQuery
    {
        return $this->hasOne($this->target_class, [
            'id' => 'target_id',
        ]);
    }

    public function rules(): array
    {
        return [
            [['permissions', 'subject', 'target_class', 'target_id'], RequiredValidator::class],
            [['body', 'response', 'subject'], StringValidator::class],
            [['permissions'],
                RangeValidator::class,
                'range' => array_keys($this->permissionOptions()),
                'allowArray' => true,
            ],
            [['target_class'],
                RangeValidator::class,
                'range' => array_keys($this->targetClassOptions()),
            ],
            [['target_id'], function ($attribute, $params, InlineValidator $validator) {
                $existValidator = \Yii::createObject(ExistValidator::class, [[
                    'targetClass' => $this->target_class,
                    'targetAttribute' => 'id',
                ]]);
                $error = '';
                if (! $existValidator->validate($this->{$attribute}, $error)) {
                    $this->addError($attribute, $error);
                }
            }],
        ];
    }

    public static function permissionMap(Project|Workspace $target): array
    {
        if ($target instanceof Project) {
            return [
                self::PERMISSION_EXPORT => Permission::PERMISSION_EXPORT,
                self::PERMISSION_READ => Permission::PERMISSION_READ,
                self::PERMISSION_WRITE => Permission::PERMISSION_SURVEY_DATA,
            ];
        } else {
            return [
                self::PERMISSION_CONTRIBUTE => Permission::PERMISSION_SURVEY_DATA,
                self::PERMISSION_EXPORT => Permission::PERMISSION_EXPORT,
                self::PERMISSION_READ => Permission::PERMISSION_READ,
                self::PERMISSION_WRITE => Permission::PERMISSION_WRITE,
            ];
        }
    }

    public function permissionOptions(): array
    {
        return [
            self::PERMISSION_CONTRIBUTE => \Yii::t('app', 'Contribute data'),
            self::PERMISSION_EXPORT => \Yii::t('app', 'Download data'),
            self::PERMISSION_OTHER => \Yii::t('app', 'Other'),
            self::PERMISSION_READ => \Yii::t('app', 'Read'),
            self::PERMISSION_WRITE => \Yii::t('app', 'Write'),
        ];
    }

    public function setTarget(Project|Workspace $target): void
    {
        if ($target instanceof Project) {
            $this->target_class = Project::class;
        } else {
            $this->target_class = Workspace::class;
        }

        $this->target_id = $target->id;
    }

    public function targetClassOptions(): array
    {
        return [
            Project::class => \Yii::t('app', 'Project'),
            Workspace::class => \Yii::t('app', 'Workspace'),
        ];
    }
}
