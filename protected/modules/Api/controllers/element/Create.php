<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\element;

use prime\helpers\ModelHydrator;
use prime\interfaces\HeramsVariableSetRepositoryInterface;
use prime\models\forms\element\Chart;
use prime\models\forms\element\SvelteElement;
use prime\modules\Api\models\Element;
use prime\repositories\ElementRepository;
use prime\values\ProjectId;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\Request;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class Create extends Action
{
    public function run(
        ModelHydrator $modelHydrator,
        HeramsVariableSetRepositoryInterface $variableSetRepository,
        ElementRepository $elementRepository,
        Request $request,
        Response $response,
    ) {
        $model = new SvelteElement($variableSetRepository);

        $model->load($request->bodyParams);
        if (! $model->validate()) {
            $response->setStatusCode(422);
            return [
                'errors' => $model->errors,
            ];
        }

        $elementId = $elementRepository->create($model);

        $response->setStatusCode(204);
        $response->headers->add('Content-Length', 0);
        $response->headers->add('Location', Url::to([
            '/api/element/view',
            'id' => $elementId,
        ], true));
        return $response;
    }
}
