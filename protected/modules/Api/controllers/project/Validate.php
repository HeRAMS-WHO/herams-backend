<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\project;

use prime\helpers\ModelHydrator;
use prime\modules\Api\models\NewProject;
use prime\modules\Api\models\UpdateProject;
use prime\repositories\ProjectRepository;
use prime\values\ProjectId;
use yii\base\Action;
use yii\web\Request;

final class Validate extends Action
{
    public function run(
        Request $request,
        ModelHydrator $modelHydrator,
        int $id = null
    ): array {
        if (! isset($id)) {
            $model = new NewProject();
        } else {
            $model = new UpdateProject(new ProjectId($id));
        }
        $modelHydrator->hydrateFromRequestArray($model, $request->bodyParams['data']);
        $model->validate(null, false);

        return $model->errors;
    }
}
