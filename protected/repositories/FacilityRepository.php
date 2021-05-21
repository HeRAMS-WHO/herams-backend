<?php
declare(strict_types=1);

namespace prime\repositories;

use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\CreateModelRepositoryInterface;
use prime\interfaces\RetrieveWriteModelRepositoryInterface;
use prime\models\ar\Facility;
use prime\models\ar\Permission;
use prime\models\forms\NewFacility as FacilityForm;
use prime\models\forms\UpdateFacility;
use prime\values\FacilityId;
use prime\values\IntegerId;
use prime\values\WorkspaceId;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class FacilityRepository implements CreateModelRepositoryInterface
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ModelHydrator $hydrator,
        private WorkspaceRepository $workspaceRepository
    ) {
    }

    public function create(Model|FacilityForm $model): FacilityId
    {
        requireParameter($model, FacilityForm::class, 'model');
        $record = new Facility();
        $record->workspace_id = $model->getWorkspace()->id()->getValue();
        $this->hydrator->hydrateActiveRecord($record, $model);
        if (!$record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new FacilityId($record->id);
    }

    public function createFormModel(IntegerId $id): FacilityForm
    {
        $model = new FacilityForm(new WorkspaceId($id->getValue()));
        $this->accessCheck->requirePermission($model, Permission::PERMISSION_CREATE);
        return $model;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function retrieveForWrite(FacilityId $id): UpdateFacility
    {
        /** @var null|Facility $facility */
        $facility = Facility::find()->andWhere(['id' => $id])->one();
        if (!isset($facility)) {
            throw new NotFoundHttpException();
        }
        $workspace = $this->workspaceRepository->retrieveForNewFacility(new WorkspaceId($facility->workspace_id));

        $form = new UpdateFacility($id, $workspace);
        $this->hydrator->hydrateFromActiveRecord($form, $facility);
        return $form;
    }

    public function save(UpdateFacility $facility): FacilityId
    {
        $record = Facility::findOne(['id' => $facility->getId()]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);
        $this->hydrator->hydrateActiveRecord($record, $facility);
        if (!$record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new FacilityId($record->id);

        // TODO: Implement save() method.
    }
}
