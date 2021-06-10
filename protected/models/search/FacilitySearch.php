<?php
declare(strict_types=1);

namespace prime\models\search;

use prime\models\ar\Facility;
use yii\base\Model;
use yii\data\DataProviderInterface;
use yii\db\ActiveQueryInterface;
use yii\validators\NumberValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

class FacilitySearch extends Model
{
    public null|string $name = null;
    public null|string $id = null;

    public function rules()
    {
        return [
            [['created'], SafeValidator::class],
            [['name'], StringValidator::class],
            [['id'], NumberValidator::class],
        ];
    }

    public function attributeLabels(): array
    {
        return Facility::labels();
    }


    public function apply(ActiveQueryInterface $query): void
    {
        if (!$this->validate()) {
            return;
        }
        if (isset($this->name)) {
            $query->andFilterWhere(['like', 'name', trim($this->name)]);
        }

        $query->andFilterWhere(['id' => $this->id]);
    }
}
