<?php


namespace prime\modules\Api\controllers;


use prime\modules\Api\controllers\response\Delete;
use prime\modules\Api\controllers\response\Update;
use SamIT\Yii2\Traits\ActionInjectionTrait;
use yii\filters\VerbFilter;
use yii\web\Controller;


/**
 * This controller handles response update notifications from LS
 */
class ResponseController extends Controller
{
    use ActionInjectionTrait;

    public $layout = '@app/views/layouts/simple';
    public function actions()
    {
        return [
            'update' => Update::class,
            'delete' => Delete::class
        ];
    }

    public function actionView()
    {
        return ['abc'];
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