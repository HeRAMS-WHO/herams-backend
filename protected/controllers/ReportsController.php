<?php

namespace prime\controllers;

use prime\components\Controller;
use prime\interfaces\ReportGeneratorInterface;
use prime\interfaces\ReportInterface;
use prime\models\Project;
use prime\models\Report;
use prime\models\Tool;
use prime\models\UserData;
use prime\reportGenerators\Test;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ReportsController extends Controller
{
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
                'previewUrl' => Url::toRoute(['reports/render-preview', 'projectId' => $projectId, 'reportGenerator' => $reportGenerator]),
                'projectId' => $projectId,
                'reportGenerator' => $reportGenerator
            ]);
        } else {
            throw new NotFoundHttpException(\Yii::t('app', '{reportGenerator} does not exist', ['reportGenerator' => $reportGenerator]));
        }
    }

    public function actionRead($id)
    {
        $report = Report::loadOne($id);
        app()->response->setContentType($report->getMimeType());
        app()->response->content = $report->getStream();
        return app()->response;
    }

    public function actionRenderFinal($projectId, $reportGenerator)
    {
        /* @todo set correct privilege */
        $project = Project::loadOne($projectId);
        if(isset(Tool::generators()[$reportGenerator])) {
            $userData = $project->getUserData($reportGenerator)->one();
            if(!isset($userData)) {
                $userData = new UserData();
            }
            $generator = Tool::generators()[$reportGenerator];
            /** @var ReportGeneratorInterface $generator */
            $generator = new $generator;
            /** @var ReportInterface $report */
            $report = $generator->render(
                $project->getResponses(),
                app()->user->identity->createSignature(),
                $userData
            );

            app()->response->setContentType($report->getMimeType());
            app()->response->content = $report->getStream();
            return app()->response;
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
            /** @var ReportGeneratorInterface $generator */
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

    public function actionPublish($projectId, $reportGenerator)
    {
        /* @todo set correct privilege */
        $project = Project::loadOne($projectId);
        if(isset(Tool::generators()[$reportGenerator])) {
            if(app()->request->isPost) {
                $userData = $project->getUserData($reportGenerator)->one();
                if (!isset($userData)) {
                    $userData = new UserData();
                }
                $generator = Tool::generators()[$reportGenerator];
                /** @var ReportGeneratorInterface $generator */
                $generator = new $generator;
                $report = Report::saveReport(
                    $generator->render(
                        $project->getResponses(),
                        app()->user->identity->createSignature(),
                        $userData
                    ),
                    $projectId,
                    $reportGenerator
                );

                if(isset($report)) {
                    app()->session->setFlash(
                        'reportPublished',
                        [
                            'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                            'text' => "Report for <strong>{$project->title}</strong> is published.",
                            'icon' => 'glyphicon glyphicon-ok'
                        ]
                    );
                } else {
                    app()->session->setFlash(
                        'reportPublished',
                        [
                            'type' => \kartik\widgets\Growl::TYPE_DANGER,
                            'text' => "Report for <strong>{$project->title}</strong> was not published! Try again.",
                            'icon' => 'glyphicon glyphicon-remove'
                        ]
                    );
                }

                return $this->redirect(
                    [
                        'projects/read',
                        'id' => $projectId
                    ]
                );

            }
            return $this->render('publish', [
                'finalUrl' => Url::toRoute(['reports/render-final', 'projectId' => $projectId, 'reportGenerator' => $reportGenerator]),
                'projectId' => $projectId,
                'reportGenerator' => $reportGenerator
            ]);
        }
    }
}