<?php

namespace prime\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\data\Sort;
use yii\validators\NumberValidator;
use yii\validators\SafeValidator;

class Response extends Model
{
    private \prime\models\ar\Workspace $workspace;
    public string $hf_id = '';
    public string $date = '';
    public string $last_updated = '';
    public $id;

    public function __construct(\prime\models\ar\Workspace $workspace)
    {
        parent::__construct([]);
        $this->workspace = $workspace;
    }

    public function rules(): array
    {
        return [
            [['hf_id', 'date', 'last_updated'], SafeValidator::class],
            [['id'], NumberValidator::class],
        ];
    }

    public function search($params): DataProviderInterface
    {
        $query = \prime\models\ar\Response::find();

        $query->with('workspace');
        $query->andFilterWhere(['workspace_id' => $this->workspace->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'id' => 'response-data-provider',
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $sort = new Sort([
            'attributes' => [
                'id',
                'hf_id',
                'date',
                'last_updated'
//                'title',
//                'created',
//                'permissionCount',
//                'facilityCount',
//                'latestUpdate' => [
//                    'asc' => ['latestUpdate' => SORT_ASC],
//                    'desc' => ['latestUpdate' => SORT_DESC],
//                    'default' => SORT_DESC,
//                ]
            ],
//            'defaultOrder' => ['latestUpdate' => SORT_DESC]
        ]);
        $dataProvider->setSort($sort);
        if (!$this->load($params) || !$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'hf_id', trim($this->hf_id)]);
        $query->andFilterWhere(['like', 'date', trim($this->date)]);
        $query->andFilterWhere(['like', 'last_updated', trim($this->last_updated)]);
        $query->andFilterWhere(['id' => $this->id]);
        return $dataProvider;
    }
}
