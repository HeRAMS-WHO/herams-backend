<?php

namespace prime\models\search;

use app\queries\ProjectQuery;
use prime\components\ActiveQuery;
use prime\models\ar\Project;
use yii\data\ActiveDataProvider;
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

    public function init()
    {
        parent::init();
        $this->query = \prime\models\ar\Workspace::find();

        if (isset($this->queryCallback)) {
            $this->query = call_user_func($this->queryCallback, $this->query);
        }


        $this->scenario = 'search';

    }

    public function rules()
    {
        return [
            [['created'], 'safe'],
            [['title'], StringValidator::class],
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
                'title',
                'created',
            ]
        ];
    }

    public function search($params)
    {
        $this->query->joinWith(['project']);
        $this->query->andFilterWhere(['tool_id' => $this->project->id]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $this->query,
            'id' => 'project-data-provider',
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'title',
                'created',
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

        $this->query->andFilterWhere(['like', \prime\models\ar\Workspace::tableName() . '.title', $this->title]);
        return $dataProvider;
    }
}