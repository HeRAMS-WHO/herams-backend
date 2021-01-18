<?php

namespace prime\models\search;

use prime\models\ar\Project;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\validators\BooleanValidator;
use yii\validators\NumberValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

class Workspace extends Model
{
    public $id;
    public $created;
    public $title;
    private Project $project;
    private \prime\models\ar\User $user;
    public $favorite;

    public function __construct(
        Project $project,
        \prime\models\ar\User $user,
        array $config = []
    ) {
        parent::__construct($config);
        $this->project = $project;
        $this->user = $user;
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
        $query = \prime\models\ar\Workspace::find();

        $query->with('project');
        $query->withFields('latestUpdate', 'facilityCount', 'responseCount', 'contributorCount');
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
                'responseCount',
                'contributorCount',
                'latestUpdate' => [
                    'asc' => ['latestUpdate' => SORT_ASC],
                    'desc' => ['latestUpdate' => SORT_DESC],
                    'default' => SORT_DESC,
                ]
            ],
            'defaultOrder' => ['latestUpdate' => SORT_DESC]
        ]);
        $dataProvider->setSort($sort);
        if (!$this->load($params) || !$this->validate()) {
            return $dataProvider;
        }



        $interval = explode(' - ', $this->created);
        if (count($interval) == 2) {
            $query->andFilterWhere([
                'and',
                ['>=', 'created', $interval[0]],
                ['<=', 'created', $interval[1] . ' 23:59:59']
            ]);
        }

        if (isset($this->favorite)) {
            $condition = ['id' => $this->user->getFavorites()->workspaces()->select('target_id')];
            if ($this->favorite) {
                $query->andWhere($condition);
            } else {
                $query->andWhere(['not', $condition]);
            }

        }
        $query->andFilterWhere(['like', 'title', trim($this->title)]);
        $query->andFilterWhere(['id' => $this->id]);
        return $dataProvider;
    }
}
