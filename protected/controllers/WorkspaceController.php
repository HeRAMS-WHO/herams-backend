<?php


namespace prime\controllers;


use prime\components\Controller;
use prime\controllers\workspace\Configure;
use prime\controllers\workspace\Create;
use prime\controllers\workspace\Import;
use prime\controllers\workspace\Limesurvey;
use prime\controllers\workspace\Share;
use prime\controllers\workspace\Update;
use yii\helpers\ArrayHelper;

class WorkspaceController extends Controller
{
    public $layout = '//admin';

    public function actions()
    {
        return [
            'configure' => Configure::class,
            'limesurvey' => Limesurvey::class,
            'update' => Update::class,
            'create' => Create::class,
            'share' => Share::class,
            'import' => Import::class

        ];
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
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