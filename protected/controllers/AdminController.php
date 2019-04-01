<?php


namespace prime\controllers;


use prime\components\Controller;
use prime\controllers\admin\Dashboard;
use prime\controllers\admin\Limesurvey;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class AdminController extends Controller
{
    public $defaultAction = 'dashboard';
    public $layout = 'admin';
    public function actions()
    {
        return [
            'dashboard' => Dashboard::class,
            'limesurvey' => Limesurvey::class
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
                            'allow' => ['dashboard'],
                            'roles' => ['@'],
                        ],
                    ]
                ]
            ]
        );

    }
}