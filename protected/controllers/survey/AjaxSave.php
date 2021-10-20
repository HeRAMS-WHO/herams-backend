<?php

declare(strict_types=1);

namespace prime\controllers\survey;

use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Survey;
use prime\models\survey\SurveyForCreate;
use prime\repositories\SurveyRepository;
use prime\values\SurveyId;
use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Request;
use yii\web\Response;

class AjaxSave extends Action
{
    private function create(
        AccessCheckInterface $accessCheck,
        ModelHydrator $hydrator,
        Request $request,
        Response $response,
        SurveyRepository $repository,
    ): array {
        $model = new SurveyForCreate();
        $accessCheck->checkPermission(new Survey(), Permission::PERMISSION_CREATE);

        $hydrator->hydrateFromRequestArray($model, $request->getBodyParams());
        if ($model->validate()) {
            return ['id' => $repository->create($model)];
        } else {
            $response->setStatusCode(422);
            return $model->errors;
        }
    }

    public function run(
        AccessCheckInterface $accessCheck,
        ModelHydrator $hydrator,
        Request $request,
        SurveyRepository $repository,
        Response $response,
        null|int $id = null,
    ): array {
        $response->format = Response::FORMAT_JSON;

        if (!$request->isPost) {
            throw new MethodNotAllowedHttpException();
        }

        if (is_null($id)) {
            return $this->create($accessCheck, $hydrator, $request, $response, $repository);
        } else {
            return $this->update($hydrator, $request, $response, $repository, $id);
        }
    }

    private function update(
        ModelHydrator $hydrator,
        Request $request,
        Response $response,
        SurveyRepository $repository,
        int $id,
    ): array {
        $model = $repository->retrieveForUpdate(new SurveyId($id));

        $hydrator->hydrateFromRequestArray($model, $request->getBodyParams());
        if ($model->validate()) {
            return ['id' => $repository->update($model)];
        } else {
            $response->setStatusCode(422);
            return $model->errors;
        }
    }
}
