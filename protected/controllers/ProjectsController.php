<?php

namespace prime\controllers;

use prime\components\Controller;
use prime\models\forms\ShareProject;
use prime\models\permissions\Permission;
use prime\models\permissions\UserProject;
use prime\models\Project;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class ProjectsController extends Controller
{
    public $defaultAction = 'list';

    public function actionCreate()
    {
        $model = new Project();
        $model->scenario = 'create';

        if (app()->request->isPost) {
            if($model->load(app()->request->data()) && $model->save()) {
                app()->session->setFlash(
                    'projectCreated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => "Project <strong>{$model->title}</strong> has been created.",
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                return $this->redirect(['projects/read', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' =>  $model
        ]);
    }

    public function actionList()
    {
        $projectsDataProvider = new ActiveDataProvider([
            'query' => app()->user->identity->getProjects()
        ]);

        return $this->render('list', [
            'projectsDataProvider' => $projectsDataProvider
        ]);
    }

    public function actionRead($id)
    {
        $project = Project::loadOne($id);
        $responseCollection = $project->getResponses();

        return $this->render('read', [
            'model' => $project,
            'responseCollection' => $responseCollection
        ]);
    }

    public function actionShare($id)
    {
        $project = Project::loadOne($id, Permission::PERMISSION_SHARE);

        $model = new UserProject([
            'target_id' => $project->id
        ]);

        if(app()->request->isPost) {
            if($model->load(app()->request->data()) && $model->save()) {
                app()->session->setFlash(
                    'projectShared',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => "Project <strong>{$model->project->title}</strong> has been shared with <strong>{$model->user->name}</strong>.",
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                return $this->redirect(['projects/read', 'id' => $model->project->id]);
            }
        }

        return $this->render('share', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = Project::loadOne($id, Permission::PERMISSION_WRITE);
        $model->scenario = 'update';

        if(app()->request->isPost) {
            if($model->load(app()->request->data()) && $model->save()) {
                app()->session->setFlash(
                    'projectUpdated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => "Project <strong>{$model->title}</strong> has been updated.",
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                return $this->redirect(['projects/read', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@']
                        ],
                    ]
                ]
            ]
        );
    }
}