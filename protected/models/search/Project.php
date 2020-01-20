<?php

namespace prime\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\validators\NumberValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

class Project extends Model
{
    public $title;
    public $id;
    public function rules()
    {
        return [
            [['title'], StringValidator::class],
            [['id'], NumberValidator::class],
        ];
    }

    public function search($params)
    {
        /** @var  $query */
        $query = \prime\models\ar\Project::find()
            ->withFields('workspaceCount', 'facilityCount', 'responseCount');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50
            ]
        ]);

        $sort = new Sort([
            'attributes' => [
                'id',
                'title',
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
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['id' => $this->id]);
        return $dataProvider;
    }
}