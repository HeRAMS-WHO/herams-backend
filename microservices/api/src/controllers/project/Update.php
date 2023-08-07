<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\api\domain\project\UpdateProject;
use herams\common\domain\project\ProjectRepository;
use herams\common\enums\ProjectVisibility;
use herams\common\helpers\ModelHydrator;
use herams\common\values\ProjectId;
use herams\common\values\Visibility;
use yii\base\Action;
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
    ): array|Response {
        $data = $request->bodyParams['data'];
        $model = new UpdateProject(new ProjectId($id));
        $modelHydrator->hydrateFromJsonDictionary($model, $data);
        if (! $model->validate()) {
            $response->setStatusCode(422);
            return $model->errors;
        }
        $visibility = ProjectVisibility::getValueFromLabel($data['projectvisibility']) ?? 'public';
        $model->visibility = new Visibility($visibility);
        $projectRepository->save($model);
        return $response;
    }
}
