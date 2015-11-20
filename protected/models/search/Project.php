<?php

namespace prime\models\search;

use prime\components\ActiveQuery;
use prime\models\ar\Tool;
use prime\models\Country;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\validators\ExistValidator;
use yii\validators\NumberValidator;
use yii\validators\RangeValidator;
use yii\validators\StringValidator;

class Project extends \prime\models\ar\Project
{
    /** @var ActiveQuery */
    public $query;

    public $countriesIds;
    public $toolIds;

    public function countriesOptions()
    {
        $result = [];
        foreach(ArrayHelper::getColumn($this->query->copy()->asArray()->all(), 'projectCountries') as $projectCountries) {
            foreach($projectCountries as $projectCountry) {
                $result[$projectCountry['country_iso_3']] = Country::findOne($projectCountry['country_iso_3'])->name;
            }
        }
        return $result;
    }

    public function init()
    {
        parent::init();
        $this->scenario = 'search';

        $this->query = \prime\models\ar\Project::find()->notClosed();
        $this->query->joinWith(['tool', 'projectCountries']);
    }

    public function rules()
    {
        return [
            [['toolIds'], RangeValidator::class, 'range' => array_keys($this->toolsOptions()), 'allowArray' => true],
            [['countriesIds'], RangeValidator::class, 'range' => array_keys($this->countriesOptions()), 'allowArray' => true],
            [['title', 'description'], StringValidator::class],
            [['created'], 'safe']
        ];
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        throw new \Exception('You cannot save a search model');
    }

    public function scenarios()
    {
        return [
            'search' => ['toolIds', 'countriesIds', 'title', 'description', 'created']
        ];
    }

    public function search($params)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->query,
            'id' => 'project-data-provider'
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'title',
                'description',
                'toolIds' => [
                    'asc' => ['tool.acronym' => SORT_ASC],
                    'desc' => ['tool.acronym' => SORT_DESC],
                    'default' => 'asc'
                ],
                'created'
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
                ['<=', 'created', $interval[1]]
            ]);
        }

        $this->query->andFilterWhere(['tool_id' => $this->toolIds]);
        $this->query->andFilterWhere(['country_iso_3' => $this->countriesIds]);
        $this->query->andFilterWhere(['like', \prime\models\ar\Project::tableName() . '.title', $this->title]);
        $this->query->andFilterWhere(['like', \prime\models\ar\Project::tableName() . '.description', $this->description]);

        return $dataProvider;
    }

    public function toolsOptions()
    {
        return ArrayHelper::map(
            $this->query->copy()->orderBy(Tool::tableName() . '.title')->all(),
            'tool.id',
            'tool.title'
        );
    }

}