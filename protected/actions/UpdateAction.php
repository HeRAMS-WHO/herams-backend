<?php
declare(strict_types=1);

namespace prime\actions;

use prime\helpers\ModelHydrator;
use prime\interfaces\RetrieveWriteModelRepositoryInterface;
use prime\repositories\FacilityRepository;
use prime\values\FacilityId;
use prime\values\IntegerId;
use prime\values\WorkspaceId;
use yii\base\Action;
use yii\web\Request;

class UpdateAction extends Action
{

    public string $view = 'update';
    public \Closure $context;
    public string $paramName = 'parent_id';


    /**
     * @throws \yii\web\NotFoundHttpException
     */
    public function run(
        Request $request,
        FacilityRepository $facilityRepository,
        ModelHydrator $modelHydrator,
        int $id
    ) {

        $model = $facilityRepository->retrieveForWrite(new FacilityId($id));
        if ($request->isPost) {
            $modelHydrator->hydrateFromRequestBody($model, $request);
            if ($model->validate(null, false)) {
                $updatedId = $facilityRepository->save($model);
                return $this->controller->redirect(['update', 'id' => $updatedId]);
            }
        }
        return $this->controller->render($this->view, ['model' => $model]);
    }
}
