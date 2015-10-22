<?php

namespace prime\controllers;

use Befound\Components\Map;
use prime\components\Controller;
use prime\interfaces\ReportGeneratorInterface;
use prime\models\Project;
use prime\models\Tool;
use prime\models\UserData;
use prime\reports\generators\Test;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ReportsController extends Controller
{
    public function actionPreview($projectId, $reportGenerator)
    {
        /* @todo set correct privilege */
        $project = Project::loadOne($projectId);
        if(isset(Tool::generators()[$reportGenerator])) {
            if(app()->request->isPost) {
                $userData = $project->getUserData($reportGenerator)->one();
                if(!isset($userData)) {
                    $userData = new UserData();
                }
                $requestData = [
                    'project_id' => $projectId,
                    'generator' => $reportGenerator,
                    'data' => app()->request->post()
                ];
                $userData->setAttributes($requestData);

                app()->response->format = Response::FORMAT_JSON;
                if($userData->save()) {
                    return [];
                } else {
                    app()->response->setStatusCode(422);
                    return $userData->errors;
                }
            }

            return $this->render('preview', [
                'previewUrl' => Url::toRoute(['reports/render-preview', 'projectId' => $projectId, 'reportGenerator' => $reportGenerator])
            ]);
        } else {
            throw new NotFoundHttpException(\Yii::t('app', '{reportGenerator} does not exist', ['reportGenerator' => $reportGenerator]));
        }
    }

    public function actionRenderPreview($projectId, $reportGenerator)
    {
        /* @todo set correct privilege */
        $project = Project::loadOne($projectId);
        if(isset(Tool::generators()[$reportGenerator])) {
            $userData = $project->getUserData($reportGenerator)->one();
            if(!isset($userData)) {
                $userData = new UserData();
            }
            $generator = Tool::generators()[$reportGenerator];
            /** @var $generator $reportGenerator */
            $generator = new $generator;
            return $generator->renderPreview(
                $project->getResponses(),
                app()->user->identity->createSignature(),
                $userData
            );
        } else {
            throw new NotFoundHttpException(\Yii::t('app', '{reportGenerator} does not exist', ['reportGenerator' => $reportGenerator]));
        }
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'rules' => [

                    ]
                ]
            ]
        );
    }
}