<?php


namespace prime\controllers;


use prime\components\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\User;

class AdminController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'verb' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'impersonate' => ['POST']
                    ]

                ]
            ]
        );
    }

    public function actionImpersonate(User $user, $id)
    {
        $user->login(\prime\models\ar\User::findOne($id));
        $this->goBack();

    }
}