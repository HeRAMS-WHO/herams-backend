<?php
declare(strict_types=1);

namespace prime\models\ar;

use Carbon\Carbon;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use prime\components\ActiveQuery;
use prime\jobs\accessRequests\CreatedNotificationJob;
use prime\models\ActiveRecord;
use prime\queries\AccessRequestQuery;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\validators\ExistValidator;
use yii\validators\InlineValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

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
class AccessRequest extends ActiveRecord
{
    const PERMISSION_READ = 'read';
    const PERMISSION_WRITE = 'write';
    const PERMISSION_EXPORT = 'export';
    const PERMISSION_OTHER = 'other';

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $jobQueue = \Yii::createObject(JobQueueInterface::class);
            $jobQueue->putJob(new CreatedNotificationJob($this->id));
        }
    }

    public function attributeLabels(): array
    {
        return [
            'body' => \Yii::t('app', 'Body'),
            'permissions' => \Yii::t('app', 'Subject'),
            'subject' => \Yii::t('app', 'Subject'),
            'target_class' => \Yii::t('app', 'Target class'),
            'target_id' => \Yii::t('app', 'Target'),
        ];
    }

    public function behaviors(): array
    {
        return [
            BlameableBehavior::class => [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
            ],
            'expiresAtBehavior' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['expires_at'],
                ],
                'value' => function () {
                    return (new Carbon())->addWeeks(2)->timestamp;
                }
            ],
            TimestampBehavior::class => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public static function find(): AccessRequestQuery
    {
        return \Yii::createObject(AccessRequestQuery::class, [get_called_class()]);
    }

    public function getCreatedByUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getTarget(): ActiveQuery
    {
        return $this->hasOne($this->target_class, ['id' => 'target_id']);
    }

    public function rules(): array
    {
        return [
            [['permissions', 'subject', 'target_class', 'target_id'], RequiredValidator::class],
            [['body', 'response', 'subject'], StringValidator::class],
            [['permissions'], RangeValidator::class, 'range' => array_keys($this->permissionOptions()), 'allowArray' => true],
            [['target_class'], RangeValidator::class, 'range' => array_keys($this->targetClassOptions())],
            [['target_id'], function ($attribute, $params, InlineValidator $validator) {
                $existValidator = \Yii::createObject(ExistValidator::class, [[
                    'targetClass' => $this->target_class,
                    'targetAttribute' => 'id',
                ]]);
                $error = '';
                if (!$existValidator->validate($this->{$attribute}, $error)) {
                    $this->addError($attribute, $error);
                }
            }],
        ];
    }

    public function permissionOptions(): array
    {
        return [
            self::PERMISSION_EXPORT => \Yii::t('app', 'Download data'),
            self::PERMISSION_OTHER => \Yii::t('app', 'Other'),
            self::PERMISSION_READ => \Yii::t('app', 'Read'),
            self::PERMISSION_WRITE => \Yii::t('app', 'Write'),
        ];
    }

    public function setTarget(Project|Workspace $target)
    {
        $this->target_class = get_class($target);
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
