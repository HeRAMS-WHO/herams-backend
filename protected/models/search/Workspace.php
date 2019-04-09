<?php

namespace prime\models\search;

use app\queries\ProjectQuery;
use prime\components\ActiveQuery;
use prime\models\ar\Project;
use prime\models\Country;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\validators\RangeValidator;
use yii\validators\StringValidator;

class Workspace extends \prime\models\ar\Workspace
{
    /**
     * @var \Closure
     */
    public $queryCallback;

    /** @var ActiveQuery */
    public $query;


    private $project;
    public function __construct(
        Project $project,
        array $config = []
    ) {
        parent::__construct($config);
        $this->project = $project;
    }

    public function countriesOptions()
    {
        $result = ArrayHelper::map(
            $this->query->copy()->all(),
            function(\prime\models\ar\Workspace $project) {
                return $project->country_iso_3;
            },
            function(\prime\models\ar\Workspace $project) {
                return Country::findOne($project->country_iso_3)->name;
            }
        );
        asort($result);
        return $result;
    }

    public function init()
    {
        parent::init();
        $this->query = \prime\models\ar\Workspace::find()->notClosed()->with('owner.profile');

        if (isset($this->queryCallback)) {
            $this->query = call_user_func($this->queryCallback, $this->query);
        }


        $this->scenario = 'search';

    }

    public function rules()
    {
        return [
            [['created', 'closed'], 'safe'],
            [['title', 'description', 'project_id', 'locality_name'], StringValidator::class],
        ];
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        throw new \Exception('You cannot save a search model');
    }

    public function scenarios()
    {
        return [
            'search' => [
                'project_id',
                'country_iso_3',
                'title',
                'description',
                'created',
                'closed',
                'locality_name'
            ]
        ];
    }

    public function search($params)
    {
        if(!app()->user->can('admin')) {
            $this->query->joinWith(['project' => function(ProjectQuery $query) {return $query->notHidden();}]);
        } else {
            $this->query->joinWith(['project']);
        }

        $this->query->andFilterWhere(['tool_id' => $this->projectId]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $this->query,
            'id' => 'project-data-provider',
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $case = Country::searchCaseStatement('country_iso_3');
        $dataProvider->setSort([
            'attributes' => [
                'id',
                'title',
                'description',
                'tool_id' => [
                    'asc' => ['acronym' => SORT_ASC],
                    'desc' => ['acronym' => SORT_DESC],
                    'default' => 'asc'
                ],

                'country_iso_3' => [
                    'asc' => [$case => SORT_ASC],
                    'desc' => [$case => SORT_DESC],
                    'default' => 'asc'
                ],
                'created',
                'closed',
                'locality_name'
            ]
        ]);

        if(!$this->load($params) || !$this->validate()) {
            return $dataProvider;
        }

        $interval = explode(' - ', $this->created);
        if(count($interval) == 2) {
            $this->query->andFilterWhere([
                'and',
                ['>=', 'created', $interval[0]],
                ['<=', 'created', $interval[1] . ' 23:59:59']
            ]);
        }

        $this->query->andFilterWhere(['tool_id' => $this->project->id]);
        $this->query->andFilterWhere(['country_iso_3' => $this->country_iso_3]);
        $this->query->andFilterWhere(['like', \prime\models\ar\Workspace::tableName() . '.title', $this->title]);
        return $dataProvider;
    }
}