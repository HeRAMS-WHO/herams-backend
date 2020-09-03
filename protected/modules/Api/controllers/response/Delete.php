<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers\response;

use prime\models\ar\Response as HeramsResponse;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

class Delete extends Action
{
    public function run(Response $response, Request $request, int $surveyId, int $responseId)
    {
        // Hardcoded bearer check.
        if (!$request->headers->has('Authorization')) {
            throw new UnauthorizedHttpException("No authorization header found");
        }
        $key = \Yii::$app->params['responseSubmissionKey'];
        if (empty($key)) {
            throw new ServerErrorHttpException('No key configured');
        }
        if (!hash_equals("Bearer $key", $request->headers->get('Authorization'))) {
            throw new ForbiddenHttpException();
        }

        $heramsResponse = HeramsResponse::findOne(['id' => $responseId, 'survey_id' => $surveyId]);
        if (!isset($heramsResponse)) {
            throw new NotFoundHttpException();
        }

        $heramsResponse->delete();
        $response->setStatusCode(204);
        return $response;
    }
}
