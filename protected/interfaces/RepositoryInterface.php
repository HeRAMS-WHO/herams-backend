<?php
declare(strict_types=1);

namespace prime\interfaces;


use prime\models\ActiveRecord;
use prime\models\forms\Facility as FacilityForm;
use prime\values\IntegerId;
use prime\values\WorkspaceId;
use yii\base\Model;

interface RepositoryInterface
{
    public function createFormModel(IntegerId $id): Model;

    public function create(Model $model): IntegerId;
}
