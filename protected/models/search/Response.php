<?php

namespace prime\models\search;

use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\validators\NumberValidator;
use yii\validators\StringValidator;

class Response extends \prime\models\ar\Response
{
    private $workspace;

    public function __construct(
        \prime\models\ar\Workspace $workspace,
        array $config = []
    ) {
        parent::__construct($config);
        $this->workspace = $workspace;
    }

    public function init()
    {
        parent::init();
        $this->scenario = self::SCENARIO_SEARCH;
    }

    public function rules()
    {
        return [
//            [['created'], SafeValidator::class],
            [['hf_id', 'date', 'last_updated'], StringValidator::class],
            [['id'], NumberValidator::class],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_SEARCH => [
                'last_updated',
                'hf_id',
                'date',
                'id'
            ]
        ];
    }

    public function search($params)
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
        if(!$this->load($params) || !$this->validate()) {
            return $dataProvider;
        }



        $interval = explode(' - ', $this->created);
        if(count($interval) == 2) {
            $query->andFilterWhere([
                'and',
                ['>=', 'created', $interval[0]],
                ['<=', 'created', $interval[1] . ' 23:59:59']
            ]);
        }

        $query->andFilterWhere(['like', 'title', trim($this->title)]);
        $query->andFilterWhere(['id' => $this->id]);
        return $dataProvider;
    }
}