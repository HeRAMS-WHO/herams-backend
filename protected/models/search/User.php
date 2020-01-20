<?php

namespace prime\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\data\Sort;
use yii\validators\NumberValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

class User extends Model
{
    public $email;
    public $name;
    public $id;
    public $created_at;
    public function rules()
    {
        return [
            [['created_at'], SafeValidator::class],
            [['email', 'name'], StringValidator::class],
            [['id'], NumberValidator::class],
        ];
    }

    public function search(array $params): DataProviderInterface
    {
        $query = \prime\models\ar\User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $sort = new Sort([
            'attributes' => [
                'id',
                'name',
                'email',
                'created_at',
            ],
            'defaultOrder' => ['id' => SORT_ASC]
        ]);
        $dataProvider->setSort($sort);
        if(!$this->load($params) || !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'email', $this->email]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['id'=> $this->id]);
        return $dataProvider;
    }
}