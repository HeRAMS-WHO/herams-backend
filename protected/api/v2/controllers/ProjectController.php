<?php


namespace prime\api\v2\controllers;

use prime\models\ar\Tool;
use yii\data\ActiveDataFilter;
use yii\rest\IndexAction;
use yii\rest\ViewAction;

class ProjectController extends Controller
{
    public function actionFacilities(int $id)
    {
        return Tool::findOne(['id' => $id])->getFacilities();
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => Tool::class,
                'dataFilter' => [
                    'class' => ActiveDataFilter::class,
                    'searchModel' => new Tool()
                ]
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => Tool::class
            ]

        ];
    }
}