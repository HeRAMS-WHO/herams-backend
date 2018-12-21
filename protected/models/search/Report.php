<?php

namespace prime\models\search;

use prime\components\ActiveQuery;
use prime\models\ar\Tool;
use prime\models\Country;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\validators\RangeValidator;
use yii\validators\StringValidator;

class Report extends \prime\models\ar\Report
{
    /** @var ActiveQuery */
    public $query;

    public $countryId;
    public $localityName;
    public $toolId;

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
        $this->query->joinWith(['tool', 'project', 'file' => function(ActiveQuery $q) {
            $q->select(['id', 'mime_type']);
        }]);
    }

    public function rules()
    {
        return [
            [['toolId'], RangeValidator::class, 'range' => array_keys($this->toolsOptions()), 'allowArray' => true],
            [['countryId'], RangeValidator::class, 'range' => array_keys($this->countriesOptions()), 'allowArray' => true],
            [['title', 'localityName'], StringValidator::class],
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
            'search' => ['toolId', 'countryId', 'title', 'published', 'localityName']
        ];
    }

    public function search($params)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->query,
            'id' => 'report-data-provider'
        ]);

        $case = Country::searchCaseStatement('country_iso_3');

        $dataProvider->setSort([
            'attributes' => [
                'toolId' => [
                    'asc' => ['tool.acronym' => SORT_ASC],
                    'desc' => ['tool.acronym' => SORT_DESC],
                    'default' => 'asc'
                ],
                'title',
                'published',
                'countryId' => [
                    'asc' => [$case => SORT_ASC],
                    'desc' => [$case => SORT_DESC],
                    'default' => 'asc'
                ],
                'localityName' => [
                    'asc' => ['project.locality_name' => SORT_ASC],
                    'desc' => ['project.locality_name' => SORT_DESC],
                    'default' => 'asc'
                ],
                'file.mime_type'
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
                ['<=', 'published', $interval[1] . ' 23:59:59']
            ]);
        }

        $this->query->andFilterWhere(['tool_id' => $this->toolId]);
        $this->query->andFilterWhere(['country_iso_3' => $this->countryId]);
        $this->query->andFilterWhere(['like', \prime\models\ar\Report::tableName() . '.title', $this->title]);
        $this->query->andFilterWhere(['like', \prime\models\ar\Workspace::tableName() . '.locality_name', $this->localityName]);
        return $dataProvider;
    }

    public function toolsOptions()
    {
        return ArrayHelper::map(
            $this->query->copy()->orderBy(Tool::tableName() . '.title')->all(),
            'tool.id',
            'tool.acronym'
        );
    }

}