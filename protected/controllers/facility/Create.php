<?php
declare(strict_types=1);

namespace prime\controllers\facility;

use prime\helpers\ModelHydrator;
use prime\interfaces\CreateModelRepositoryInterface;
use prime\models\forms\NewFacility;
use prime\objects\BreadcrumbCollection;
use prime\repositories\FacilityRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\WorkspaceId;
use yii\base\Action;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;

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
        Model $model,
        Controller $controller,
        FacilityRepository $repository,
        Response $response
    ): null|Response {
        if ($request->isPost) {
            $hydrator->hydrateFromRequestArray($model, $request->bodyParams);
            if ($model->validate()) {
                $response->statusCode = 201;

                $response->headers->add('X-Suggested-Location', Url::to(['update', 'id' => $repository->create($model)], true));

                $response->headers->add('Location', Url::to(['update', 'id' => $repository->create($model)], true));
                return $response;
            } else {
                \Yii::error($model->errors);
                throw new BadRequestHttpException(print_r($model->errors, true));
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
        Response $response,
        int $workspaceId,
    ) {
        $response->headers->add('Access-Control-Allow-Origin', '*');
        $response->headers->add('Access-Control-Allow-Credentials', 'true');
        $id = new WorkspaceId($workspaceId);
        $workspaceForNewFacility = $workspaceRepository->retrieveForNewFacility($id);
        $model = new NewFacility($workspaceForNewFacility);

        if ($request->isPost) {
            return $this->handlePost($request, $hydrator, $model, $this->controller, $facilityRepository, $response);
        }



        $breadcrumbCollection = $this->controller->view->getBreadcrumbCollection();
        $breadcrumbCollection
            ->add($this->workspaceRepository->retrieveForBreadcrumb($id));
        return $this->controller->render('create', ['model' => $model]);
    }
}
