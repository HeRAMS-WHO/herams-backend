<?php

declare(strict_types=1);

namespace prime\models\forms\survey;

use prime\traits\DisableYiiLoad;
use yii\base\Model;
use yii\validators\RequiredValidator;

class CreateForm extends Model
{
    use DisableYiiLoad;

    public array $config = [];

    public function attributeLabels(): array
    {
        return [
            'config' => \Yii::t('app', 'Config'),
        ];
    }

    public function rules(): array
    {
        return [
            [['config'], RequiredValidator::class],
        ];
    }
}
