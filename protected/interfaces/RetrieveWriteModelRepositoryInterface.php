<?php
declare(strict_types=1);

namespace prime\interfaces;


use prime\values\IntegerId;
use yii\base\Model;
use yii\db\ActiveRecord;

interface RetrieveWriteModelRepositoryInterface
{
    /**
     * TODO: Tighten return type
     */
    public function retrieveForWrite(IntegerId $id): Model;

    public function save(Model $model): IntegerId;
}
