<?php

declare(strict_types=1);

namespace herams\common\helpers;

use InvalidArgumentException;
use yii\base\Model;
use yii\web\HttpException;
use yii\web\Response;

/**
 * @yii-depends \Yii::$app->response
 */
final class ModelValidator
{
    public function checkIfOkay(Model $model): void
    {
        if (! $this->validateModel($model)) {
            throw new HttpException(422, json_encode($model->errors));
        }
    }

    public function validateModel(Model $model): bool
    {
        return $model->validate(clearErrors: false);
    }

    public function renderValidationErrors(
        Model $model,
        Response $response
    ): Response {
        if (! $model->hasErrors()) {
            throw new InvalidArgumentException(
                "Model has no validation errors"
            );
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
     */
    public function validateAndRenderForValidationEndpoint(
        Model $model,
        Response $response
    ): Response {
        $model->validate();
        $response->setStatusCode(200);
        $response->data = [
            'errors' => $model->errors,
        ];
        return $response;
    }
}
