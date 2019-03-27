<?php

namespace prime\controllers;

use kartik\widgets\Growl;
use prime\components\Controller;
use prime\controllers\project\Index;
use prime\controllers\project\Summary;
use prime\controllers\project\Update;
use prime\controllers\project\View;
use prime\controllers\project\Pages;
use prime\controllers\project\Workspaces;
use prime\factories\GeneratorFactory;
use prime\models\ar\Project;
use prime\models\forms\Share;
use prime\models\permissions\Permission;
use yii\data\ActiveDataProvider;
use yii\filters\PageCache;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\Request;
use yii\web\Session;
use yii\web\User;

class ProjectController extends Controller
{
    public $layout = 'admin';

    public function actionCreate(
        Request $request,
        Session $session
    ) {
        $model = new Project();

        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->save())
            {
                $session->setFlash(
                    'toolCreated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app', "Tool {tool} is created.", ['tool' => $model->title]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );

                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
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
        $tool = Project::loadOne($id);
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

    public function actionShare(Session $session, Request $request, $id)
    {
        $tool = Project::loadOne($id, [], Permission::PERMISSION_SHARE);
        $model = new Share($tool, [], [
            'permissions' => [
                Permission::PERMISSION_INSTANTIATE
            ]
        ]);
        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->createRecords()) {
                $session->setFlash(
                    'projectShared',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app',
                            "Tool {modelName} has been shared with: {users}",
                            [
                                'modelName' => $tool->title,
                                'users' => implode(', ', array_map(function($model){return $model->name;}, $model->getUsers()->all()))
                            ]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                $model = new Share($tool, []);
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
        $tool = Project::loadOne($permission->target_id, [], Permission::PERMISSION_SHARE);
        if($permission->delete()) {
            $session->setFlash(
                'toolShared',
                [
                    'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                    'text' => \Yii::t(
                        'app',
                        "Stopped sharing tool <strong>{modelName}</strong> with: <strong>{user}</strong>",
                        [
                            'modelName' => $tool->title,
                            'user' => $user->identity->name
                        ]
                    ),
                    'icon' => 'glyphicon glyphicon-trash'
                ]
            );
        }
        $this->redirect(['/tools/share', 'id' => $tool->id]);
    }

    public function actions()
    {
        return [
            'update' => Update::class,
            'index' => Index::class,
            'view' => View::class,
            'summary' => Summary::class,
            'workspaces' => Workspaces::class
        ];
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'share-delete' => ['delete'],
                        'delete' => ['delete']
                    ]
                ],

                'pageCache' => [
                    'class' => PageCache::class,
                    'enabled' => !YII_DEBUG,
                    'only' => ['summary'],
                    'variations' => [
                        \Yii::$app->request->getQueryParam('id')
                    ],
                    'duration' => 120,
                ],
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['summary'],
                            'roles' => ['@'],
                        ],
                    ]
                ]
            ]
        );
    }
}
