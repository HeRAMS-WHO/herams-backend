<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\element;

use prime\helpers\ModelHydrator;
use prime\interfaces\HeramsVariableSetRepositoryInterface;
use prime\models\ar\RawElement;
use prime\models\forms\element\Chart;
use prime\models\forms\element\SvelteElement;
use prime\modules\Api\models\Element;
use prime\repositories\ElementRepository;
use prime\values\ElementId;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\Request;
use yii\web\Response;

class Update extends Action
{
    public function run(
        ModelHydrator $modelHydrator,
        HeramsVariableSetRepositoryInterface $variableSetRepository,
        ElementRepository $elementRepository,
        Request $request,
        Response $response,
        int $id
    ) {
        $model = $elementRepository->retrieveForUpdate(new ElementId($id));

        $modelHydrator->hydrateFromRequestArray($model, $request->bodyParams);
        if (! $model->validate()) {
            $response->setStatusCode(422);
            return $model->errors;
        }

//        $elementRepository->update()

        $response->setStatusCode(201);
        $response->headers->add('Location', Url::to([
            '/api/element/view',
            'id' => $model->id,
        ], true));
        return $model;
    }
}
