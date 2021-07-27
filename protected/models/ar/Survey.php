<?php
declare(strict_types=1);

namespace prime\models\ar;


use prime\models\ActiveRecord;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;

/**
 * @property int $id
 * @property array $config
 */
class Survey extends ActiveRecord
{
    public function rules(): array
    {
        return [
            [['config'], RequiredValidator::class]
        ];
    }

    public static function labels(): array
    {
        return array_merge(parent::labels(), [
            'config' => \Yii::t('app', 'Survey configuration')
        ]);
    }

    public function getTitle(): string
    {
        return $this->config['title'] ?? \Yii::t('app', "Survey without title, id {id}", ['id' => $this->id]);
    }



}
