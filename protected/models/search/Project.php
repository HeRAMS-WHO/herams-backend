<?php

namespace prime\models\search;

use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\validators\NumberValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

class Project extends \prime\models\ar\Project
{
    public function __construct(
        array $config = []
    ) {
        parent::__construct($config);
    }

    public function init()
    {
        parent::init();
        $this->scenario = self::SCENARIO_SEARCH;
    }

    public function rules()
    {
        return [
            [['created'], SafeValidator::class],
            [['title'], StringValidator::class],
            [['id'], NumberValidator::class],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_SEARCH => [
                'title',
                'created',
                'id'
            ]
        ];
    }

    public function search($params)
    {
        $baseTable = self::tableName();

        $query = \prime\models\ar\Project::find()
            ->withFields('workspaceCount', 'facilityCount', 'responseCount')
            ->with('workspaces');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'id' => 'project-data-provider',
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $sort = new Sort([
            'attributes' => [
                'id',
                'title' => [
                    'asc' => ['title' => SORT_ASC],
                    'desc' => ['title' => SORT_DESC],
                ],
                'created',
                'workspaceCount',
                'facilityCount',
                'responseCount',
            ],
            'defaultOrder' => ['title' => SORT_ASC]
        ]);
        $dataProvider->setSort($sort);
        if(!$this->load($params) || !$this->validate()) {
            return $dataProvider;
        }



//        $interval = explode(' - ', $this->created);
//        if(count($interval) == 2) {
//            $query->andFilterWhere([
//                'and',
//                ['>=', 'created', $interval[0]],
//                ['<=', 'created', $interval[1] . ' 23:59:59']
//            ]);
//        }
//
//        $query->andFilterWhere(['like', "$baseTable.[[title]]", $this->title]);
//        $query->andFilterWhere(["$baseTable.[[id]]" => $this->id]);
        return $dataProvider;
    }
}