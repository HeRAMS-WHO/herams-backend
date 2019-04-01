<?php

namespace prime\controllers;

use kartik\widgets\Growl;
use prime\components\Controller;
use prime\controllers\project\Create;
use prime\controllers\project\Index;
use prime\controllers\project\Share;
use prime\controllers\project\Summary;
use prime\controllers\project\Update;
use prime\controllers\project\View;
use prime\controllers\project\Pages;
use prime\controllers\project\Workspaces;
use prime\factories\GeneratorFactory;
use prime\models\ar\Project;
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

    /**
     * Deletes a tool.
     * @param $id
     * @throws HttpException Method not allowed if request is not a DELETE request
     */
    public function actionDelete(Request $request, Session $session,  $id)
    {
        $project = Project::loadOne($id);
        if ($project->delete() !== false) {



            $session->setFlash(
                'toolDeleted',
                [
                    'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                    'text' => \Yii::t('app', "Tool <strong>{modelName}</strong> has been removed.",
                        ['modelName' => $project->title]),
                    'icon' => 'glyphicon glyphicon-trash'
                ]
            );

        } else {
            $session->setFlash(
                'toolDeleted',
                [
                    'type' => \kartik\widgets\Growl::TYPE_DANGER,
                    'text' => \Yii::t('app', "Tool <strong>{modelName}</strong> could not be removed.",
                        ['modelName' => $project->title]),
                    'icon' => 'glyphicon glyphicon-trash'
                ]
            );
        }
        $this->redirect($this->defaultAction);
    }
    public function actions()
    {
        return [
            'create' => Create::class,
            'update' => Update::class,
            'index' => Index::class,
            'view' => View::class,
            'summary' => Summary::class,

            'share' => Share::class,
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
                            'actions' => ['view', 'summary', 'index', 'update', 'workspaces'],
                            'roles' => ['@'],
                        ],
                    ]
                ]
            ]
        );
    }
}
