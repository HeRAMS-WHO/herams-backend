<?php

declare(strict_types=1);

namespace prime\interfaces;

use herams\common\values\IntegerId;
use yii\base\Model;

interface RetrieveWriteModelRepositoryInterface
{
    /**
     * TODO: Tighten return type
     */
    public function retrieveForWrite(IntegerId $id): Model;

    public function save(Model $model): IntegerId;
}
