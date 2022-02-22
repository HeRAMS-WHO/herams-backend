<?php

declare(strict_types=1);

namespace prime\models\search;

use prime\models\ar\Survey;
use yii\base\Model;
use yii\validators\FilterValidator;
use yii\validators\NumberValidator;
use yii\validators\SafeValidator;

class SurveySearch extends Model
{
    public null|string $title = null;
    public null|string $id = null;

    public function rules(): array
    {
        return [
            [['title'], FilterValidator::class, 'filter' => 'trim'],
            [['title'], SafeValidator::class],
            [['id'], NumberValidator::class],
        ];
    }

    public function attributeLabels(): array
    {
        return Survey::labels();
    }
}
