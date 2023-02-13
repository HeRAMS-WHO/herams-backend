<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\api\models\ProjectSummary;
use herams\common\models\Permission;
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
        /** @var null|ProjectSummary $project */
        $project = ProjectSummary::find()->with('mainPages')->where([
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
            'statusText',
        ]));
    }
}
