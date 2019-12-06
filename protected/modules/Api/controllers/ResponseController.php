<?php


namespace prime\controllers;


use prime\controllers\response\Delete;
use prime\controllers\response\Update;
use SamIT\Yii2\Traits\ActionInjectionTrait;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * This controller handles response update notifications from LS
 */
class ResponseController extends Controller
{
    use ActionInjectionTrait;
    public $enableCsrfValidation = false;

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