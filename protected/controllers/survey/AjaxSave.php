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
use yii\web\Request;
use yii\web\Response;

class AjaxSave extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
        ModelHydrator $hydrator,
        Request $request,
        SurveyRepository $repository,
        Response $response,
        null|int $id = null,
    ) {
        $response->format = Response::FORMAT_JSON;

        if (is_null($id)) {
            $model = new SurveyForCreate();
            $accessCheck->checkPermission(new Survey(), Permission::PERMISSION_CREATE);
        } else {
            $model = $repository->retrieveForUpdate(new SurveyId($id));
        }

        if ($request->isPost) {
            $hydrator->hydrateFromRequestArray($model, $request->getBodyParams());
            if ($model->validate()) {
                if (is_null($id)) {
                    return ['id' => $repository->create($model)];
                } else {
                    return ['id' => $repository->update($model)];
                }
            } else {
                $response->setStatusCode(422);
                return $model->errors;
            }
        }

        throw new BadRequestHttpException();
    }
}
