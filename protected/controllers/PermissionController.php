<?php


namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\permission\Delete;
use prime\controllers\permission\Grant;
use prime\controllers\permission\Revoke;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class PermissionController extends Controller
{
    public function actions()
    {
        return [
            'delete' => Delete::class,
            'grant' => Grant::class,
            'revoke' => Revoke::class,
        ];
    }


    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['delete'],
                        'grant' => ['post'],
                        'revoke' => ['post']
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
