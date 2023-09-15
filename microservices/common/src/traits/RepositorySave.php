<?php

declare(strict_types=1);

namespace herams\common\traits;

use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\models\ActiveRecord;
use herams\common\models\PermissionOld;
use yii\base\Model;

trait RepositorySave
{
    private readonly AccessCheckInterface $accessCheck;

    private readonly ActiveRecordHydratorInterface $activeRecordHydrator;

    private function internalSave(ActiveRecord $record, Model $model): void
    {
        $this->accessCheck->requirePermission($record, PermissionOld::PERMISSION_WRITE);
        $this->activeRecordHydrator->hydrateActiveRecord($model, $record);
        if (empty($record->getDirtyAttributes())) {
            \Yii::debug([
                'message' => 'Record has no dirty attributes',
                'source' => $model->attributes,
                'target' => $record->attributes,
            ]);
        }
        if (! $record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
    }
}
