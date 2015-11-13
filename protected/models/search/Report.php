<?php

namespace prime\models\search;

use yii\data\ActiveDataProvider;

class Report extends \prime\models\ar\Report
{
    public function rules()
    {
        return [];
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        throw new \Exception('You cannot save a search model');
    }

    public function scenarios()
    {
        return [
            'search' => []
        ];
    }

    public function search($params)
    {
        $this->scenario = 'search';
        $query = \prime\models\ar\Report::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        if(!$this->load($params) || !$this->validate())
        {
            return $dataProvider;
        }

        return $dataProvider;
    }

}