<?php

declare(strict_types=1);

namespace prime\models\search;

use herams\common\models\PermissionOld;
use prime\components\FilteredActiveDataProvider;
use prime\models\ar\read\Project as ProjectRead;
use yii\base\Model;
use yii\data\Sort;
use yii\validators\NumberValidator;
use yii\validators\StringValidator;

class Project extends Model
{
    public $title;

    public $id;

    public function rules(): array
    {
        return [
            [['title'], StringValidator::class],
            [['id'], NumberValidator::class],
        ];
    }

    public function search($params, \yii\web\User $user): FilteredActiveDataProvider
    {
        $query = ProjectRead::find()
            ->withFields('contributorCount', 'workspaceCount', 'responseCount', 'pageCount', 'facilityCount');
        $dataProvider = new FilteredActiveDataProvider([
            'filter' => function (\herams\common\models\Project $project) use ($user) {
                return ! $project->isHidden() || $user->can(PermissionOld::PERMISSION_READ, $project);
            },
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $sort = new Sort([
            'attributes' => [
                'id',
                'title',
                'created_date',
                'workspaceCount',
                'facilityCount',
                'responseCount',
                'contributorCount',
            ],
            'defaultOrder' => [
                'title' => SORT_ASC,
            ],
        ]);
        $dataProvider->setSort($sort);
        if (! $this->load($params) || ! $this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        return $dataProvider;
    }
}
