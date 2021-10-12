<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\helpers\ArrayHelper;
use prime\models\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\validators\RequiredValidator;

/**
 * Attributes
 * @property array $config
 * @property string|null $created_at
 * @property int $id
 * @property string|null $update_at
 *
 * Virtual fields
 * @property-read string $title
 */
class Survey extends ActiveRecord
{
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                TimestampBehavior::class => [
                    'class' => TimestampBehavior::class,
                ],
            ]
        );
    }

    public function getTitle(): string
    {
        return $this->config['title'] ?? \Yii::t('app', "Survey without title, id {id}", ['id' => $this->id]);
    }

    public static function labels(): array
    {
        return array_merge(parent::labels(), [
            'config' => \Yii::t('app', 'Survey configuration'),
            'title' => \Yii::t('app', 'Title'),
        ]);
    }

    public function rules(): array
    {
        return [
            [['config'], RequiredValidator::class]
        ];
    }
}
