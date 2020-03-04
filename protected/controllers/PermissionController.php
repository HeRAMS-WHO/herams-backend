<?php


namespace prime\controllers;


use prime\components\Controller;
use prime\controllers\permission\Delete;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class PermissionController extends Controller
{
    public function actions()
    {
        return [
            'delete' => Delete::class
        ];
    }


    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['delete']
                    ]
                ],
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ]
                ]
            ]
        );
    }
}