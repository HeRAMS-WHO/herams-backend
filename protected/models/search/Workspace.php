<?php

namespace prime\models\search;

use prime\models\ar\Project;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\validators\NumberValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

class Workspace extends \prime\models\ar\Workspace
{
    private $project;

    public function __construct(
        Project $project,
        array $config = []
    ) {
        parent::__construct($config);
        $this->project = $project;
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
                'project_id',
                'title',
                'created',
                'id'
            ]
        ];
    }

    public function search($params)
    {
        $query = \prime\models\ar\Workspace::find();

        $query->with('project');
        $query->withFields('latestUpdate', 'facilityCount', 'responseCount', 'permissionCount');
        $query->andFilterWhere(['tool_id' => $this->project->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'id' => 'workspace-data-provider',
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $sort = new Sort([
            'attributes' => [
                'id',
                'title',
                'created',
                'permissionCount',
                'facilityCount',
                'latestUpdate' => [
                    'asc' => ['latestUpdate' => SORT_ASC],
                    'desc' => ['latestUpdate' => SORT_DESC],
                    'default' => SORT_DESC,
                ]
            ],
            'defaultOrder' => ['latestUpdate' => SORT_DESC]
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