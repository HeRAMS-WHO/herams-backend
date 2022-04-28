<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers;

use prime\modules\Api\controllers\element\Create;
use prime\modules\Api\models\Element;
use yii\rest\ViewAction;

class ElementController extends Controller
{

    public function actions(): array
    {
        return [
            'create' => Create::class,
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => Element::class
            ]
        ];
    }


}
