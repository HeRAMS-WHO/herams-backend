<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers\workspace;

use prime\helpers\ModelHydrator;
use prime\interfaces\ModelHydratorInterface;
use prime\models\ar\Workspace;
use prime\modules\Api\models\NewProject;
use prime\modules\Api\models\NewWorkspace;
use prime\modules\Api\models\UpdateProject;
use prime\modules\Api\models\UpdateWorkspace;
use prime\repositories\WorkspaceRepository;
use prime\values\ProjectId;
use prime\values\WorkspaceId;
use yii\base\Action;
use yii\web\Request;

class Validate extends Action
{

    public function run(
        Request $request,
        ModelHydrator $modelHydrator,
        int $id = null
    ): array
    {
        if (!isset($id)) {
            $model = new NewWorkspace();
        } else {
            $model = new UpdateWorkspace(new WorkspaceId($id));
        }
        $modelHydrator->hydrateFromRequestArray($model, $request->bodyParams['data']);
        $model->validate(null, false);

        return $model->errors;
    }
}
