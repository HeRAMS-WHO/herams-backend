<?php
declare(strict_types=1);

namespace prime\controllers\facility;

use prime\components\NotificationService;
use prime\helpers\ModelHydrator;
use prime\repositories\FacilityRepository;
use prime\values\FacilityId;
use prime\values\StringId;
use yii\base\Action;
use yii\web\Request;

class Update extends Action
{

    /**
     * @throws \yii\web\NotFoundHttpException
     */
    public function run(
        Request $request,
        FacilityRepository $facilityRepository,
        NotificationService $notificationService,
        ModelHydrator $modelHydrator,
        string $id
    ) {
        $facilityId = new FacilityId($id);
        $model = $facilityRepository->retrieveForWrite($facilityId);

        if ($request->isPost) {
            $modelHydrator->hydrateFromRequestBody($model, $request);
            if ($model->validate(null, false)) {
                $updatedId = $facilityRepository->save($model);
                $notificationService->success(\Yii::t('app', 'Facility updated'));
                return $this->controller->redirect(['update', 'id' => $updatedId]);
            }
        }
        return $this->controller->render('update', [
            'model' => $model,
            'tabMenuModel' => $facilityRepository->retrieveForTabMenu($facilityId)
        ]);
    }
}
