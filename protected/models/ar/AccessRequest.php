<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\components\ActiveQuery;
use prime\models\ActiveRecord;
use prime\queries\AccessRequestQuery;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
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
 * @property int $id
 * @property string $responded_at
 * @property string $response
 * @property string $subject
 * @property string $target_class
 * @property int $target_id
 *
 * @property Project|Workspace $target
 */
class AccessRequest extends ActiveRecord
{
    const PERMISSION_READ = 'read';
    const PERMISSION_WRITE = 'write';
    const PERMISSION_EXPORT = 'export';
    const PERMISSION_OTHER = 'other';

    public function behaviors(): array
    {
        return [
            BlameableBehavior::class => [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
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

    public function getTarget(): ActiveQuery
    {
        return $this->hasOne($this->target_class, ['id' => 'target_id']);
    }

    public function rules(): array
    {
        return [
            [['subject', 'target_class', 'target_id'], RequiredValidator::class],
            [['body', 'response', 'subject'], StringValidator::class],
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

    public function targetClassOptions(): array
    {
        return [
            Project::class => \Yii::t('app', 'Project'),
            Workspace::class => \Yii::t('app', 'Workspace'),
        ];
    }
}
