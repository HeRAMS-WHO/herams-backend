<?php

namespace prime\modules\Api\controllers;

use prime\modules\Api\controllers\response\Delete;
use prime\modules\Api\controllers\response\Update;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * This controller handles response update notifications from LS
 */
class ResponseController extends Controller
{
    public $layout = '@app/views/layouts/simple';
    public function actions()
    {
        return [
            'update' => Update::class,
            'delete' => Delete::class
        ];
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            VerbFilter::class => [
                'class' => VerbFilter::class,
                'actions' => [
                    'update' => ['post'],
                    'delete' => ['delete']
                ]
            ]
        ]);
    }
}
