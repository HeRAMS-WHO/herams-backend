<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\common\models\Permission;
use herams\common\models\Project;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\User;

final class Summary extends Action
{
    public function run(
        User $user,
        int $id
    ) {
        /** @var null|Project $project */
        $project = Project::find()->with('mainPages')->where([
            'id' => $id,
        ])->one();
        if (! isset($project)) {
            throw new NotFoundHttpException();
        }

        if (! $user->can(Permission::PERMISSION_SUMMARY, $project)) {
            throw new ForbiddenHttpException();
        }
        return $this->controller->asJson($project->toArray([], [
            'primaryTierCount',
            'secondaryTierCount',
            'tertiaryTierCount',
            'unknownTierCount',
            //            'functionalityCounts',
            //            'subjectAvailabilityCounts',
            'statusText',
        ]));
    }
}