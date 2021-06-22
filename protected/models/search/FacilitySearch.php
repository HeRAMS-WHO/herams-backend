<?php
declare(strict_types=1);

namespace prime\models\search;

use prime\models\ar\Facility;
use yii\base\Model;
use yii\data\DataProviderInterface;
use yii\db\ActiveQueryInterface;
use yii\validators\FilterValidator;
use yii\validators\NumberValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

class FacilitySearch extends Model
{
    public null|string $name = null;
    public null|string $id = null;

    public function rules(): array
    {
        return [
            [['name'], FilterValidator::class, 'filter' => 'trim'],
            [['created'], SafeValidator::class],
            [['name'], StringValidator::class],
            [['id'], NumberValidator::class],
        ];
    }

    public function attributeLabels(): array
    {
        return Facility::labels();
    }
}
