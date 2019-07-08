<?php


namespace prime\controllers;


use prime\actions\DeleteAction;
use prime\components\Controller;
use prime\controllers\element\Create;
use prime\controllers\element\Preview;
use prime\controllers\element\Update;
use prime\models\ar\Element;
use prime\models\permissions\Permission;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\User;

class ElementController extends Controller
{
    public $layout = 'admin';
    public function actions()
    {
        return [
            'update' => Update::class,
            'create' => Create::class,
            'preview' => Preview::class,
            'delete' => [
                'class' => DeleteAction::class,
                'permission' => function(User $user, Element $element) {
                    return $user->can(Permission::PERMISSION_ADMIN, $element->page->project);
                },
                'query' => Element::find(),
                'redirect' => function(Element $element) {
                    return ['page/update', 'id' => $element->page_id];
                }
            ]
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