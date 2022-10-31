<?php

declare(strict_types=1);

namespace herams\api\controllers\workspace;

use prime\helpers\ModelHydrator;
use herams\api\models\NewWorkspace;
use herams\api\models\UpdateWorkspace;
use prime\values\WorkspaceId;
use yii\base\Action;
use yii\web\Request;

class Validate extends Action
{
    public function run(
        Request $request,
        ModelHydrator $modelHydrator,
        int $id = null
    ): array {
        if (! isset($id)) {
            $model = new NewWorkspace();
        } else {
            $model = new UpdateWorkspace(new WorkspaceId($id));
        }
        $modelHydrator->hydrateFromRequestArray($model, $request->bodyParams['data']);
        $model->validate(null, false);

        return $model->errors;
    }
}
