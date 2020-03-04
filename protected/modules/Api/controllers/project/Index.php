<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers\project;


use prime\models\ar\Project;
use yii\base\Action;
use yii\web\UrlManager;

class Index extends Action
{
    public function run(UrlManager $urlManager)
    {
        $result = [];
        /** @var Project $project */
        foreach(Project::find()
                ->withFields('latestDate', 'workspaceCount', 'facilityCount')
                    ->each() as $project) {
            $result[] = [
                'id' => $project->id,
                'name' => $project->title,
                'url' => $urlManager->createAbsoluteUrl(['project/view', 'id' => $project->id]),
                'facilityCount' => $project->facilityCount,
                'workspaceCount' => $project->workspaceCount,
                'contributorCount' => $project->contributorCount,
                'latitude' => (float) $project->latitude,
                'longitude' => (float) $project->longitude,
                'latestDate' => $project->latestDate
            ];
        }
        return $this->controller->asJson($result);
    }
}