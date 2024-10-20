<?php

declare(strict_types=1);

namespace prime\models\search;

use herams\common\domain\facility\Facility;
use yii\base\Model;
use yii\validators\FilterValidator;
use yii\validators\NumberValidator;
use yii\validators\SafeValidator;

class FacilitySearch extends Model
{
    public null|string $name = null;

    public null|string $id = null;

    public function rules(): array
    {
        return [
            [['name'],
                FilterValidator::class,
                'filter' => 'trim',
            ],
            [['name'], SafeValidator::class],
            [['id'], NumberValidator::class],
        ];
    }

    public function attributeLabels(): array
    {
        return Facility::labels();
    }
}
