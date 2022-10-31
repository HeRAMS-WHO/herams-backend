<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use prime\helpers\ModelHydrator;
use herams\api\models\NewProject;
use herams\api\models\UpdateProject;
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
