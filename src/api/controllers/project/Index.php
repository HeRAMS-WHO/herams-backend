<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use prime\models\ar\read\Project;
use yii\base\Action;
use yii\web\UrlManager;

class Index extends Action
{
    public function run(
        UrlManager $urlManager
    )
    {
        $data = [];
        foreach (Project::find()
            ->orderBy([
                'id' => 'asc',
            ])
            ->andWhere([
                'visibility' => Project::VISIBILITY_PUBLIC,
            ])
            ->withFields(
                'latestDate',
                'workspaceCount',
                'facilityCount',
                'contributorPermissionCount',
                'responseCount'
            )->all() as $project) {
            $data[] = $project->toArray([], ['coordinatorName']);
        }
        return $this->controller->asJson($data);
    }
}
