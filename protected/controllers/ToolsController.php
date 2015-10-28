<?php

namespace prime\controllers;

use prime\components\Controller;
use prime\models\permissions\Permission;
use prime\models\Tool;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\Response;

class ToolsController extends Controller
{
    public $defaultAction = 'list';

    public function actionCreate()
    {
        $model = new Tool();
        $model->scenario = 'create';

        if(app()->request->isPost) {
            $model->load(app()->request->data());
            if($model->load(app()->request->data()) && $model->save())
            {
                app()->session->setFlash(
                    'toolCreated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => "Tool <strong>{$model->title}</strong> is created.",
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

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionDepDropGenerators()
    {
        app()->response->format = Response::FORMAT_JSON;
        if(app()->request->isPost) {
            $parents = app()->request->data()['depdrop_parents'];
            $generators = [];
            $tools = Tool::findAll($parents);
            foreach($tools as $tool) {
                foreach($tool->getGenerators() as $key => $value) {
                    $generator = new $value();
                    $generators[] = [
                        'id' => $key,
                        'name' => $generator->title
                    ];
                }
            }
            return [
                'output' => $generators,
                'selected' => ''
            ];
        }
        return [
            'output' => '',
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

    public function actionUpdate($id)
    {
        $model = Tool::loadOne($id, Permission::PERMISSION_WRITE);
        $model->scenario = 'update';

        if(app()->request->isPut) {
            if($model->load(app()->request->data()) && $model->save()) {
                app()->session->setFlash(
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

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['list', 'read', 'generators'],
                            'roles' => ['@'],
                        ],
                    ]
                ]
            ]
        );
    }

}