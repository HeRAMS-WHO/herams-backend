<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers\element;

use prime\helpers\ModelHydrator;
use prime\interfaces\HeramsVariableSetRepositoryInterface;
use prime\models\forms\element\Chart;
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
        int $projectId
    ) {

        $variableSet = $variableSetRepository->retrieveForProject(new ProjectId($projectId));
        $model = new Chart($variableSet);
        $modelHydrator->hydrateFromRequestArray($model, $request->bodyParams);
        if (!$model->validate()) {
            $response->setStatusCode(422);

            return $model->errors;
        }


        \Yii::error($model->attributes);

        $createdElement = $elementRepository->create($model);

        $response->setStatusCode(201);
        $response->headers->add('Location', Url::to(['/api/element/view', 'id' => $createdElement->id], true));
        return $model;
    }

}
