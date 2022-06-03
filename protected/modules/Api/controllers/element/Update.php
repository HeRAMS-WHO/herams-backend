<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers\element;

use prime\helpers\ModelHydrator;
use prime\models\forms\element\Chart;
use prime\modules\Api\models\Element;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\Request;
use yii\web\Response;

class Update extends Action
{
    public function run(
        ModelHydrator $modelHydrator,
        Request $request,
        Response $response,
        int $id
    ) {
        $model = new Chart();
        $modelHydrator->hydrateFromRequestArray($model, $request->bodyParams);
        if (!$model->validate()) {
            $response->setStatusCode(422);
            return $model->errors;
        }

        \Yii::error($model->attributes);
        var_dump($model->attributes); die();

        $model->save(false);

        $response->setStatusCode(201);
        $response->headers->add('Location', Url::to(['/api/element/view', 'id' => $model->id], true));
        return $model;
    }

}
