<?php

namespace prime\controllers;

use prime\components\Controller;
use prime\models\Tool;
use yii\data\ActiveDataProvider;
use yii\helpers\FileHelper;
use yii\helpers\Url;

class ToolsController extends Controller
{
    public $defaultAction = 'list';

    public function accessRules() {
        $rules = [
            [
                'allow',
                'actions' => ['index', 'read'],
                'roles' => ['@']
            ]
        ];
        return array_merge($rules, parent::accessRules());
    }

    public function actionCreate()
    {
        $tool = new Tool();
        $tool->scenario = 'create';

        if(app()->request->isPost) {
            // use a transation for the case that the image could not be saved
            $transaction = app()->db->beginTransaction();
            if($tool->load(app()->request->data()) && $tool->save())
            {
                if ($tool->saveTempImage()) {
                    $transaction->commit();
                    app()->session->setFlash(
                        'toolCreated',
                        [
                            'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                            'text' => "Tool <strong>{$tool->title}</strong> is created.",
                            'icon' => 'glyphicon glyphicon-ok'
                        ]
                    );

                    return $this->redirect(
                        [
                            'tools/read/',
                            'id' => $tool->id
                        ]
                    );
                } else {
                    $transaction->rollBack();

                    app()->session->setFlash(
                        'toolNotCreated',
                        [
                            'type' => \kartik\widgets\Growl::TYPE_DANGER,
                            'text' => "The image for <strong>{$tool->title}</strong> could not be saved.",
                            'icon' => 'glyphicon glyphicon-ok'
                        ]
                    );
                }
            }
        }

        return $this->render('create', [
            'model' => $tool
        ]);
    }

    public function actionList()
    {
        $toolsDataProvider = new ActiveDataProvider([
            'query' => Tool::find()
        ]);

        return $this->render('list', [
            'toolsDataProvider' => $toolsDataProvider
        ]);
    }

    public function actionRead($id)
    {
        $tool = Tool::findOne($id);
        return $this->render('read',[
            'tool' => $tool
        ]);
    }

}