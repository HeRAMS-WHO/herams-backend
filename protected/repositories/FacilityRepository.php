<?php
declare(strict_types=1);

namespace prime\repositories;

use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\RepositoryInterface;
use prime\models\ar\Facility;
use prime\models\ar\Permission;
use prime\models\forms\Facility as FacilityForm;
use prime\values\FacilityId;
use prime\values\IntegerId;
use prime\values\WorkspaceId;
use yii\base\Model;

class FacilityRepository implements RepositoryInterface
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ModelHydrator $hydrator
    ) {
    }

    public function create(Model|FacilityForm $model): FacilityId
    {
        requireParameter($model, FacilityForm::class, 'model');
        $record = new Facility();
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
}
