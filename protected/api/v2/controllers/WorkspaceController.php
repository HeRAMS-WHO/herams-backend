<?php


namespace prime\api\v2\controllers;


use prime\models\ar\Workspace;
use yii\base\DynamicModel;
use yii\data\ActiveDataFilter;
use yii\rest\IndexAction;
use yii\rest\ViewAction;

class WorkspaceController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => Workspace::class,
                'dataFilter' => [
                    'class' => ActiveDataFilter::class,
                    'searchModel' => (new DynamicModel(['tool_id' => null]))
                        ->addRule('tool_id', 'integer')
                ]
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => Workspace::class
            ]

        ];
    }
}