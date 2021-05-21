<?php
declare(strict_types=1);

namespace prime\models\search;

use prime\values\WorkspaceId;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\validators\BooleanValidator;
use yii\validators\NumberValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

class FacilitySearch extends Model
{
    public null|string $name;
    public null|string $id;
    public function __construct(
        private WorkspaceId $workspaceId,
        array $config = []
    ) {
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['created'], SafeValidator::class],
            [['title'], StringValidator::class],
            [['id'], NumberValidator::class],
            [['favorite'], BooleanValidator::class]
        ];
    }

    public function search($params)
    {
        $query = \prime\models\ar\Facility::find();

        $query->andFilterWhere(['workspace_id' => $this->workspaceId->getValue()]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'id' => 'facility-data-provider',
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        if (!$this->load($params) || !$this->validate()) {
            return $dataProvider;
        }



        $query->andFilterWhere(['like', 'name', trim($this->name)]);
        $query->andFilterWhere(['id' => $this->id]);
        return $dataProvider;
    }
}
