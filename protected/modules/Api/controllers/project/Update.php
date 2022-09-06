<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\project;

use prime\helpers\ModelHydrator;
use prime\interfaces\HeramsVariableSetRepositoryInterface;
use prime\models\ar\RawElement;
use prime\models\forms\element\Chart;
use prime\models\forms\element\SvelteElement;
use prime\modules\Api\models\Element;
use prime\modules\Api\models\UpdateProject;
use prime\repositories\ElementRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\ElementId;
use prime\values\ProjectId;
use prime\values\WorkspaceId;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\Request;
use yii\web\Response;

final class Update extends Action
{
    public function run(
        ModelHydrator $modelHydrator,
        Request $request,
        ProjectRepository $projectRepository,
        Response $response,
        int $id
    ) {
        $model = new UpdateProject(new ProjectId($id));
        $modelHydrator->hydrateFromJsonDictionary($model, $request->bodyParams['data']);
        if (! $model->validate()) {
            $response->setStatusCode(422);
            return $model->errors;
        }
        $projectRepository->save($model);

        $response->setStatusCode(200);
        return $response;
    }
}
