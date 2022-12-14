<?php

declare(strict_types=1);

namespace prime\exceptions;

use yii\base\Model;
use yii\web\UnprocessableEntityHttpException;

class UnprocessableEntityException extends UnprocessableEntityHttpException
{
    public function __construct(
        private readonly Model $model
    ) {
        parent::__construct();
    }

    public static function forModel(Model $model): self
    {
        return new self($model);
    }
}
