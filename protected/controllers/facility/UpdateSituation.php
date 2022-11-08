<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\values\FacilityId;
use prime\components\NotificationService;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\Request;
use yii\web\Response;

final class UpdateSituation extends Action
{
    public function run(
        FacilityRepository $facilityRepository,
        ModelHydrator $modelHydrator,
        NotificationService $notificationService,
        WorkspaceRepository $workspaceRepository,
        Request $request,
        Response $response,
        string $id
    ) {
        $facilityId = new FacilityId($id);

        $model = $facilityRepository->retrieveForUpdateSituation($facilityId);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);
        $projectId = $workspaceRepository->getProjectId($workspaceId);
        if ($request->isPost) {
            $response->format = Response::FORMAT_JSON;
            $modelHydrator->hydrateFromRequestArray($model, $request->bodyParams);
            if ($model->validate(null, false)) {
                $response->headers->add('X-Suggested-Location', Url::to([
                    'responses',
                    'id' => $facilityRepository->saveUpdateSituation($model),
                ], true));
                $notificationService->success(\Yii::t('app', 'Facility situation updated'));
                return $response;
            } else {
                $response->statusCode = 422;
                return $model->errors;
            }
        }

        return $this->controller->render('updateSituation', [
            'model' => $model,
            'tabMenuModel' => $facilityRepository->retrieveForTabMenu($facilityId),
            'projectId' => $projectId,
        ]);
    }
}
