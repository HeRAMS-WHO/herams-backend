<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use prime\components\NotificationService;
use prime\helpers\ModelHydrator;
use prime\repositories\FacilityRepository;
use prime\values\FacilityId;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\UnprocessableEntityHttpException;

class Update extends Action
{

    /**
     * @throws \yii\web\NotFoundHttpException
     */
    public function run(
        Request $request,
        Response $response,
        FacilityRepository $facilityRepository,
        NotificationService $notificationService,
        ModelHydrator $modelHydrator,
        string $id
    ) {
        $facilityId = new FacilityId($id);
        $model = $facilityRepository->retrieveForUpdate($facilityId);

        if ($request->isPost) {
            $response->format = Response::FORMAT_JSON;
            $modelHydrator->hydrateFromRequestArray($model, $request->bodyParams);
            \Yii::error($request->bodyParams);
            \Yii::error($model->attributes);
            if ($model->validate(null, false)) {
                $response->headers->add('X-Suggested-Location', Url::to(['update', 'id' => $facilityRepository->save($model)], true));
                $notificationService->success(\Yii::t('app', 'Facility updated'));
                return $response;
            } else {
                $response->statusCode = 422;
                return $model->errors;
            }
        }
        return $this->controller->render('update', [
            'model' => $model,
            'tabMenuModel' => $facilityRepository->retrieveForTabMenu($facilityId)
        ]);
    }
}
