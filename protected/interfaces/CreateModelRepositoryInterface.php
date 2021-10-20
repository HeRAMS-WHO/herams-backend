<?php

declare(strict_types=1);

namespace prime\interfaces;

use prime\values\Id;
use prime\values\IntegerId;
use yii\base\Model;

interface CreateModelRepositoryInterface
{
    public function createFormModel(IntegerId $id): Model;

    public function create(Model $model): Id;
}
