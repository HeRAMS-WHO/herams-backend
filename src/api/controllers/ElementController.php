<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\api\controllers\element\Create;
use herams\api\controllers\element\Update;
use herams\api\models\Element;
use yii\rest\ViewAction;

class ElementController extends Controller
{
    public function actions(): array
    {
        return [
            'create' => Create::class,
            'update' => Update::class,
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => Element::class,
            ],
        ];
    }
}
