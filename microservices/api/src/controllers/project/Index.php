<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\common\models\Project;
use yii\base\Action;

class Index extends Action
{
    public function run(
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
