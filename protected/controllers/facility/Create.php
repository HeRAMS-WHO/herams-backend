<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use prime\helpers\ModelHydrator;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\forms\facility\CreateForm as CreateModel;
use prime\repositories\FacilityRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\WorkspaceId;
use yii\base\Action;
use yii\base\InvalidArgumentException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Request;
use yii\web\Response;

use function PHPUnit\Framework\assertInstanceOf;

class Create extends Action
{
    public function __construct(
        $id,
        $controller,
        private WorkspaceRepository $workspaceRepository,
        $config = []
    ) {
        parent::__construct($id, $controller, $config);
    }

    final protected function handlePost(
        Request $request,
        ModelHydrator $hydrator,
        CreateModel $model,
        FacilityRepository $repository,
        Response $response
    ): null|Response {
        $hydrator->hydrateFromRequestArray($model, $request->bodyParams);
        if ($model->validate()) {
            $id = $repository->create($model);
            $response->statusCode = 201;
            $response->headers->add('X-Suggested-Location', Url::to(['update', 'id' => $id], true));
            $response->headers->add('Location', Url::to(['update', 'id' => $id], true));
            return $response;
        } else {
            \Yii::error($model->errors);
            throw new BadRequestHttpException(print_r($model->errors, true));
        }
    }

    /**
     * @throws \yii\web\NotFoundHttpException
     */
    public function run(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        ModelHydrator $hydrator,
        Request $request,
        Response $response,
        int $workspaceId,
    ) {
        $response->headers->add('Access-Control-Allow-Origin', '*');
        $response->headers->add('Access-Control-Allow-Credentials', 'true');
        $id = new WorkspaceId($workspaceId);
        try {
            $workspace = $workspaceRepository->retrieveForNewFacility($id);
            $model = $facilityRepository->createFormModel($workspace);
        } catch (InvalidArgumentException $e) {
            // In case it is a Limesurvey project we need to create a new response
            $workspace = $workspaceRepository->retrieveForRead($id);
            assertInstanceOf(WorkspaceForLimesurvey::class, $workspace);
            return $this->controller->render('createForLimesurvey', ['model' => $workspace]);
        }

        if ($request->isPost) {
            return $this->handlePost($request, $hydrator, $model, $facilityRepository, $response);
        }

        return $this->controller->render('create', ['model' => $model]);
    }
}
