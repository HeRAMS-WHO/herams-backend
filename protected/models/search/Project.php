<?php

namespace prime\models\search;

use prime\components\FilteredActiveDataProvider;
use prime\models\ar\Permission;
use SamIT\abac\AuthManager;
use SamIT\abac\values\Authorizable;
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
            ->withFields('workspaceCount', 'facilityCount', 'responseCount', 'contributorPermissionCount');
        $dataProvider = new FilteredActiveDataProvider([
            'filter' => function (\prime\models\ar\Project $project) use ($user) {
                return !$project->isHidden() || $user->can(Permission::PERMISSION_READ, $project);
            },
            'query' => $query,
            'pagination' => [
                'pageSize' => 50
            ]
        ]);

        /** @var AuthManager $abacManager */
        $abacManager = \Yii::$app->abacManager;
        foreach($abacManager->getRepository()->search(null, new Authorizable("", \prime\models\ar\Project::class), null))
        {
            // We do nothing here, iterating means the results will be cached for later use.
        }
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
