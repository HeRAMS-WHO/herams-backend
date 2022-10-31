<?php

declare(strict_types=1);

namespace prime\helpers;

use yii\base\Model;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;

/**
 * @yii-depends \Yii::$app->response
 */
final class ModelValidator
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

    /**
     * Renders a response for use by a validation endpoint.
     * Validation endpoints differ from normal ones because their HTTP status code will be 200 even when the payload
     * has errors; this makes sense because the validation works and the result is a list of errors.
     * @param Model $model
     * @param Response $response
     * @return Response
     */
    public function renderForValidationEndpoint(Model $model, Response $response): Response
    {

        $response->setStatusCode(200);
        $response->data = [
            'errors' => $model->errors,
        ];
        return $response;
    }
}
