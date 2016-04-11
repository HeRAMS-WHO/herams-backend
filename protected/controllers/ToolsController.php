<?php

namespace prime\controllers;

use prime\components\Controller;
use prime\factories\GeneratorFactory;
use prime\models\permissions\Permission;
use prime\models\ar\Tool;
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