<?php

namespace prime\controllers;

use kartik\widgets\Growl;
use prime\components\Controller;
use prime\factories\GeneratorFactory;
use prime\interfaces\ReportGeneratorInterface;
use prime\models\ar\UserData;
use prime\models\forms\Share;
use prime\models\permissions\Permission;
use prime\models\ar\Tool;
use prime\objects\ResponseCollection;
use prime\objects\Signature;
use prime\reportGenerators\base\Generator;
use SamIT\LimeSurvey\JsonRpc\Client;
use yii\web\HttpException;
use yii\web\Request;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Session;
use yii\web\User;

class ToolsController extends Controller
{
    public $defaultAction = 'list';

    public function actionCreate(Request $request, Session $session)
    {
        $model = new Tool();
        $model->scenario = 'create';

        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->save())
            {
                $session->setFlash(
                    'toolCreated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app', "Tool {tool} is created.", ['{tool}' => $model->title]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );

                return $this->redirect(
                    [
                        'tools/read',
                        'id' => $model->id
                    ]
                );
            } else {
                $session->setFlash('toolNotCreated', [
                    'type' => Growl::TYPE_WARNING,
                    'text' => \Yii::t('app', 'Failed to create tool.') . print_r($model->errors, true)
                ]);
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionResponses(
        Request $request,
        Response $response,
        User $user,
        $id
    ) {
        $tool = Tool::loadOne($id);
        return $this->render('dashboard/responses', [
            'tool' => $tool
        ]);
    }

    public function actionDashboard(
        Request $request,
        Response $response,
        User $user,
        $id
    )
    {
        $tool = Tool::loadOne($id);
        $projectSearch = new \prime\models\search\Project();
        $projectSearch->query = $tool->getProjects();
        $projectsDataProvider = $projectSearch->search($request->queryParams);

        return $this->render('dashboard', [
            'model' => $tool,
            'projectSearch' => $projectSearch,
            'projectsDataProvider' => $projectsDataProvider

        ]);
    }

    /**
     * Get a list of generators for use in dependent dropdowns.
     * @param Response $response
     * @param Request $request
     * @param array $depdrop_parents
     * @return array
     */
    public function actionDependentGenerators(
        Response $response,
        Request $request,
        array $depdrop_parents
    )
    {
        $response->format = Response::FORMAT_JSON;
        $generators = [];
        $options = GeneratorFactory::options();

        foreach(Tool::findAll(['id' => $depdrop_parents]) as $tool) {
            $generatorCount = count($tool->generators);
            foreach ($tool->generators as $key => $value) {
                if (isset($options[$value])) {
                    $generators[] = [
                        'id' => $value,
                        'name' => $options[$value]
                    ];
                } else {
                    unset($tool->generators[$key]);
                }
            }
            if ($generatorCount > count($tool->generators)) {
                $tool->save();
            }
        }


        return [
            'output' => $generators,
            'selected' => ''
        ];
    }

    public function actionList()
    {
        $toolsDataProvider = new ActiveDataProvider([
            'query' => Tool::find()->userCan(Permission::PERMISSION_READ)
        ]);

        return $this->render('list', [
            'toolsDataProvider' => $toolsDataProvider
        ]);
    }

    public function actionRead($id)
    {
        return $this->render('read',[
            'model' => Tool::loadOne($id)
        ]);
    }
    
    public function actionRequestAccess($id)
    {
        $model = Tool::loadOne($id);
        return $this->render('requestAccess', [
            'model' => $model
        ]);
    }

    public function actionUpdate(Request $request, Session $session, $id)
    {
        $model = Tool::loadOne($id);
        $model->scenario = 'update';

        if($request->isPut) {
            if($model->load($request->bodyParams) && $model->save()) {
                $session->setFlash(
                    'toolUpdated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => "Tool <strong>{$model->title}</strong> is updated.",
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );

                return $this->redirect(
                    [
                        'tools/read',
                        'id' => $model->id
                    ]
                );
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes a tool.
     * @param $id
     * @throws HttpException Method not allowed if request is not a DELETE request
     */
    public function actionDelete(Request $request, Session $session,  $id)
    {
        if (!$request->isDelete) {
            throw new HttpException(405);
        } else {
            $tool = Tool::loadOne($id);
            if ($tool->delete()) {
                $session->setFlash(
                    'toolDeleted',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app', "Tool <strong>{modelName}</strong> has been removed.",
                            ['modelName' => $tool->title]),
                        'icon' => 'glyphicon glyphicon-trash'
                    ]
                );

            } else {
                $session->setFlash(
                    'toolDeleted',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_DANGER,
                        'text' => \Yii::t('app', "Tool <strong>{modelName}</strong> could not be removed.",
                            ['modelName' => $tool->title]),
                        'icon' => 'glyphicon glyphicon-trash'
                    ]
                );
            }
            $this->redirect($this->defaultAction);
        }
    }

    public function actionProgress(Response $response, $id)
    {
        return '';
        $tool = $project = Tool::loadOne($id);
        $report = $tool->getProgressReport();
        if (!isset($report)) {
            throw new \HttpException(404, "Progress report for project not found.");
        }
        $response->setContentType($report->getMimeType());
        $response->content = $report->getStream();
        return $response;
    }


    public function actionShare(Session $session, Request $request, $id)
    {
        $tool = Tool::loadOne($id, [], Permission::PERMISSION_SHARE);
        $model = new Share($tool, []);

        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->createRecords()) {
                $session->setFlash(
                    'projectShared',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app',
                            "Project <strong>{modelName}</strong> has been shared with: <strong>{users}</strong>",
                            [
                                'modelName' => $tool->title,
                                'users' => implode(', ', array_map(function($model){return $model->name;}, $model->getUsers()->all()))
                            ]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                $model = new Share($tool, [$tool->owner_id]);
            }
        }

        return $this->render('share', [
            'model' => $model,
            'tool' => $tool
        ]);
    }

    public function actionShareDelete(User $user, Request $request, Session $session, $id)
    {
        $permission = Permission::findOne($id);
        //User must be able to share project in order to delete a share
        $project = Project::loadOne($permission->target_id, [], Permission::PERMISSION_SHARE);
        if($permission->delete()) {
            $session->setFlash(
                'projectShared',
                [
                    'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                    'text' => \Yii::t(
                        'app',
                        "Stopped sharing project <strong>{modelName}</strong> with: <strong>{user}</strong>",
                        [
                            'modelName' => $project->title,
                            'user' => $user->identity->name
                        ]
                    ),
                    'icon' => 'glyphicon glyphicon-trash'
                ]
            );
        }
        $this->redirect(['/projects/share', 'id' => $project->id]);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['read'],
                            'roles' => ['@'],
                        ],
                    ]
                ]
            ]
        );
    }

}