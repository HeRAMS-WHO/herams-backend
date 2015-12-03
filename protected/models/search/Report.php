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

class Report extends \prime\models\ar\Report
{
    /** @var ActiveQuery */
    public $query;

    public $toolIds;
    public $countriesIds;

    public function countriesOptions()
    {
        return ArrayHelper::map(
            array_filter($this->query->copy()->select('country_iso_3')->distinct()->column()),
            function($model) {
                return $model;
            },
            function($model) {
                return Country::findOne($model)->name;
            }
        );
    }

    public function init()
    {
        parent::init();
        $this->scenario = 'search';

        $this->query = \prime\models\ar\Report::find();
        $this->query->joinWith(['tool', 'project']);
    }

    public function rules()
    {
        return [
            [['toolIds'], RangeValidator::class, 'range' => array_keys($this->toolsOptions()), 'allowArray' => true],
            [['countryId'], RangeValidator::class, 'range' => array_keys($this->countriesOptions()), 'allowArray' => true],
            [['title'], StringValidator::class],
            [['published'], 'safe']
        ];
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        throw new \Exception('You cannot save a search model');
    }

    public function scenarios()
    {
        return [
            'search' => ['toolIds', 'countryId', 'title', 'published']
        ];
    }

    public function search($params)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->query,
            'id' => 'report-data-provider'
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'toolIds' => [
                    'asc' => ['tool.acronym' => SORT_ASC],
                    'desc' => ['tool.acronym' => SORT_DESC],
                    'default' => 'asc'
                ],
                'title',
                'published'
            ]
        ]);

        if(!$this->load($params) || !$this->validate()) {
            return $dataProvider;
        }

        $interval = explode(' - ', $this->published);
        if(count($interval) == 2) {
            $this->query->andFilterWhere([
                'and',
                ['>=', 'published', $interval[0]],
                ['<=', 'published', $interval[1]]
            ]);
        }

        $this->query->andFilterWhere(['tool_id' => $this->toolIds]);
        $this->query->andFilterWhere(['country_iso_3' => $this->countriesIds]);
        $this->query->andFilterWhere(['like', \prime\models\ar\Report::tableName() . '.title', $this->title]);

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