<?php

namespace prime\models\search;

use app\queries\ProjectQuery;
use prime\components\ActiveQuery;
use prime\models\ar\Project;
use prime\models\ar\Response;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\db\Expression;
use yii\db\Query;
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
        $baseTable = self::tableName();
        $query = \prime\models\ar\Workspace::find();

        $query->with('project');
        $query->withFields('latestUpdate', 'facilityCount', 'responseCount', 'permissionCount');
        $query->andFilterWhere(["$baseTable.[[tool_id]]" => $this->project->id]);
//        $query->addSelect([
//            "$baseTable.*"
//        ]);

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
                'title',
                'created',
                'latestUpdate' => [
                    'asc' => ['last_update' => SORT_ASC],
                    'desc' => ['last_update' => SORT_DESC],
                    'default' => SORT_DESC,
                ]
            ]
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

        $query->andFilterWhere(['like', "$baseTable.[[title]]", $this->title]);
        $query->andFilterWhere(["$baseTable.[[id]]" => $this->id]);
        return $dataProvider;
    }
}