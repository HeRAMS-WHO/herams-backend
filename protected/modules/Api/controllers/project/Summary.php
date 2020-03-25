<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers\project;

use prime\models\ar\Project;
use prime\models\permissions\Permission;
use yii\base\Action;
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
            'subjectAvailabilityCounts' => $project->getSubjectAvailabilityCounts(),
            'functionalityCounts' => $project->getFunctionalityCounts(),
            'typeCounts' => $project->getTypeCounts(),
            'facilityCount' => $project->facilityCount,
            'contributorCount' => $project->contributorCount
        ]);
    }
}
