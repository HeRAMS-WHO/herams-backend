<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers\response;

use prime\helpers\LimesurveyDataLoader;
use prime\models\ar\Project;
use prime\models\ar\ResponseForLimesurvey as HeramsResponse;
use prime\models\ar\WorkspaceForLimesurvey;
use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

class Update extends Action
{

    public function run(
        Request $request,
        Response $response,
        LimesurveyDataLoader $loader
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

        if (!isset($data, $data['id'], $data['token'], $request->bodyParams['surveyId'])) {
            throw new BadRequestHttpException();
        }
        $key = [
            'survey_id' => $request->getBodyParam('surveyId'),
            'id' => $data['id']
        ];

        // Find the project.
        $project = Project::find()->andWhere(['base_survey_eid' => $request->getBodyParam('surveyId')])->one();
        if (!(isset($project))) {
            throw new NotFoundHttpException('Unknown survey ID: ' . $request->getBodyParam('surveyId'));
        }

        /** @var WorkspaceForLimesurvey|null $workspace */
        $workspace = WorkspaceForLimesurvey::find()->andWhere(['token' => $data['token'], 'tool_id' => $project->id])->one();
        if (!isset($workspace)) {
            throw new NotFoundHttpException('Unknown token');
        }

        $heramsResponse = HeramsResponse::findOne($key) ?? new HeramsResponse($key);

        $loader->loadData($data, $workspace, $heramsResponse);

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
