<?php


namespace prime\controllers;


use prime\models\ar\Project;
use prime\models\ar\Response as HeramsResponse;
use prime\models\ar\Workspace;
use SamIT\Yii2\Traits\ActionInjectionTrait;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * This controller handles response update notifications from LS
 */
class ResponseController extends Controller
{
    use ActionInjectionTrait;
    public $enableCsrfValidation = false;
    public function actionUpdate(
        Request $request,
        Response $response
    ) {
        // Hardcoded bearer check.
        if (!$request->headers->has('Authorization')) {
            throw new UnauthorizedHttpException();
        }
        $key = \Yii::$app->params['responseSubmissionKey'];
        if (empty($key)) {
            throw new ServerErrorHttpException('No key configured');
        }
        if (!hash_equals("Bearer $key", $request->headers->get('Authorization'))) {
            throw new ForbiddenHttpException();
        }
        $data = $request->getBodyParam('response');
        $key = [
            'survey_id' => $request->getBodyParam('surveyId'),
            'id' => $data['id']
        ];

        // Find the project.
        $project = Project::find()->andWhere(['base_survey_eid' => $request->getBodyParam('surveyId')])->one();
        if (!(isset($project))) {
            throw new NotFoundHttpException('Unknown survey ID');
        }

        $workspace = Workspace::find()->inProject($project)->andWhere(['token' => $data['token']])->one();
        if (!isset($workspace)) {
            throw new NotFoundHttpException('Unknown token');
        }

        $heramsResponse = HeramsResponse::findOne($key) ?? new HeramsResponse($key);
        $heramsResponse->loadData($data, $workspace);

        if ($heramsResponse->save()) {
            $response->setStatusCode(204);
        } else {
            $response->setStatusCode(422);
            $response->format = Response::FORMAT_JSON;
            $response->data = $heramsResponse->errors;
        }

        return $response;
    }


}