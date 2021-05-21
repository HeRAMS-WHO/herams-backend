<?php
declare(strict_types=1);

namespace prime\controllers\facility;

use prime\helpers\ModelHydrator;
use prime\interfaces\CreateModelRepositoryInterface;
use prime\models\forms\NewFacility;
use prime\repositories\FacilityRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\WorkspaceId;
use yii\base\Action;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;

class Create extends Action
{
    final protected function handlePost(
        Request $request,
        ModelHydrator $hydrator,
        Model $model,
        Controller $controller,
        CreateModelRepositoryInterface $repository,
    ): null|Response {
        if ($request->isPost) {
            $hydrator->hydrateFromRequestBody($model, $request);
            if ($model->validate()) {
                return $controller->redirect(['update', 'id' => $repository->create($model)]);
            }
        }
        return null;
    }


    /**
     * @throws \yii\web\NotFoundHttpException
     */
    public function run(
        WorkspaceRepository $workspaceRepository,
        FacilityRepository $facilityRepository,
        ModelHydrator $hydrator,
        Request $request,
        int $parent_id,
    ) {
        $id = new WorkspaceId($parent_id);

        $workspaceForNewFacility = $workspaceRepository->retrieveForNewFacility($id);
        $model = new NewFacility($workspaceForNewFacility);

        return $this->handlePost($request, $hydrator, $model, $this->controller, $facilityRepository)
            ?? $this->controller->render('create', ['model' => $model]);
    }
}
