<?php

declare(strict_types=1);

namespace herams\api\controllers\element;

use herams\common\helpers\ModelHydrator;
use herams\common\interfaces\HeramsVariableSetRepositoryInterface;
use herams\common\values\ElementId;
use prime\repositories\ElementRepository;
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
