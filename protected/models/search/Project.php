<?php

namespace prime\models\search;

use prime\components\FilteredActiveDataProvider;
use prime\models\ar\Permission;
use yii\base\Model;
use yii\data\Sort;
use yii\validators\NumberValidator;
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

    public function search($params, \yii\web\User $user): FilteredActiveDataProvider
    {
        /** @var  $query */
        $query = \prime\models\ar\Project::find()
            ->withFields('workspaceCount', 'facilityCount', 'responseCount', 'contributorPermissionCount', 'pageCount');
        $dataProvider = new FilteredActiveDataProvider([
            'filter' => function (\prime\models\ar\Project $project) use ($user) {
                return !$project->isHidden() || $user->can(Permission::PERMISSION_READ, $project);
            },
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
        if (!$this->load($params) || !$this->validate()) {
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
