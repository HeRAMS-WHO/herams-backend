<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\api\models\ProjectSummary;
use yii\base\Action;

class Index extends Action
{
    public function run(
    ) {
        $data = [];
        /**
         * @var ProjectSummary $project
         */
        foreach (ProjectSummary::find()
            ->orderBy([
                'id' => 'asc',
            ])
            ->andWhere([
                'visibility' => ProjectSummary::VISIBILITY_PUBLIC,
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
