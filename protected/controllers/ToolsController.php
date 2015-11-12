<?php

namespace prime\controllers;

use prime\components\Controller;
use prime\models\permissions\Permission;
use prime\models\Tool;
use SamIT\LimeSurvey\JsonRpc\Client;
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
            $model->load($request->data());
            if($model->load($request->data()) && $model->save())
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

    public function actionDepDropGenerators(
        Response $response,
        Request $request
    )
    {
        $response->format = Response::FORMAT_JSON;
        if($request->isPost) {
            $parents = $request->data()['depdrop_parents'];
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

    public function actionUpdate(Request $request, Session $session, $id)
    {
        $model = Tool::loadOne($id, Permission::PERMISSION_WRITE);
        $model->scenario = 'update';

        if($request->isPut) {
            if($model->load($request->data()) && $model->save()) {
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
     * @todo Implement this.
     * @param $id
     */
    public function actionDelete($id)
    {

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