<?php


namespace prime\controllers;


use prime\components\Controller;
use prime\controllers\element\Create;
use prime\controllers\element\Update;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class ElementController extends Controller
{
    public $layout = 'admin';
    public function actions()
    {
        return [
            'update' => Update::class,
            'create' => Create::class
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
                            'roles' => ['admin'],
                        ],
                        [
                            'allow' => false,
                        ]
                    ]
                ]
            ]
        );
    }
}