<?php

namespace prime\models\search;

use prime\models\ar\WorkspaceForLimesurvey;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\data\Sort;
use yii\validators\NumberValidator;
use yii\validators\SafeValidator;

class Response extends Model
{
    public string $date = '';
    public string $hf_id = '';
    public $id;
    public string $updated_at = '';

    public function __construct(
        private WorkspaceForLimesurvey $workspace,
        array $config = []
    ) {
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['hf_id', 'date', 'updated_at'], SafeValidator::class],
            [['id'], NumberValidator::class],
        ];
    }

    public function search($params): DataProviderInterface
    {
        $query = \prime\models\ar\ResponseForLimesurvey::find();

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
                'updated_at'
//                'title',
//                'created_at',
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
        $query->andFilterWhere(['like', 'updated_at', trim($this->updated_at)]);
        $query->andFilterWhere(['id' => $this->id]);
        return $dataProvider;
    }
}
