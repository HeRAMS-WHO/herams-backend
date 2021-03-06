<?php
declare(strict_types=1);

namespace prime\controllers;

use prime\actions\DeleteAction;
use prime\components\Controller;
use prime\controllers\element\Create;
use prime\controllers\element\Preview;
use prime\controllers\element\Update;
use prime\models\ar\Element;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class ElementController extends Controller
{
    public $layout = Controller::LAYOUT_ADMIN_TABS;

    public function actions(): array
    {
        return [
            'update' => Update::class,
            'create' => Create::class,
            'preview' => Preview::class,
            'delete' => [
                'class' => DeleteAction::class,
                'query' => Element::find(),
                'redirect' => function (Element $element) {
                    return ['page/update', 'id' => $element->page_id];
                }
            ]
        ];
    }

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
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
