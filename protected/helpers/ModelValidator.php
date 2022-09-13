<?php

declare(strict_types=1);

namespace prime\helpers;

use yii\base\Model;
use yii\web\Response;

/**
 * @yii-depends \Yii::$app->response
 */
class ModelValidator
{
    public function validateModel(Model $model): bool
    {
        return $model->validate(clearErrors: false);
    }

    public function renderValidationErrors(Model $model, Response $response): Response
    {
        if (! $model->hasErrors()) {
            throw new \InvalidArgumentException("Model has no validation errors");
        }
        $response->setStatusCode(422);
        $response->data = [
            'errors' => $model->errors,
        ];
        return $response;
    }

    public function renderForValidationEndpoint(Model $model, Response $response): Response
    {
        $response->setStatusCode(200);
        $response->data = $model->errors;
        return $response;
    }
}
