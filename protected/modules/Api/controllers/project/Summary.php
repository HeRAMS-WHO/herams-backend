<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers\project;

use prime\models\ar\Project;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UrlManager;
use yii\web\User;

class Summary extends Action
{
    public function run(
        User $user,
        int $id
    ) {
        /** @var Project $project */
        $project = Project::find()->with('pages')->where(['id' => $id])->one();
        if (!isset($project)) {
            throw new NotFoundHttpException();
        }

        if (!$project->isHidden() && !$user->can(Permission::PERMISSION_READ, $project)) {
            throw new ForbiddenHttpException();
        }

        return $this->controller->asJson([
            'id' => $project->id,
            'title' => $project->title,
            'status' => $project->status == Project::STATUS_TARGET ? 'target':'ongoing',
            'links' => array_filter([
                'dashboard' => $project->getPages()->exists() && \Yii::$app->user->can(Permission::PERMISSION_READ, $project)
                    ? Url::to(['/project/view', 'id' => $project->id]) : null,
                'workspaces' => Url::to(['/project/workspaces', 'id' => $project->id])
            ]),
            'subjectAvailabilityCounts' => $project->getSubjectAvailabilityCounts(),
            'functionalityCounts' => $project->getFunctionalityCounts(),
            'typeCounts' => $project->getTypeCounts(),
            'facilityCount' => $project->facilityCount,
            'contributorCount' => $project->contributorCount,
        ]);
    }
}
