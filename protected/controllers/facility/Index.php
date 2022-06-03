<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use prime\models\ar\read\Facility;
use yii\base\Action;
use yii\data\ActiveDataProvider;

class Index extends Action
{
    public function run()
    {
        $query = Facility::find();
        return $this->controller->render('index', [
            'facilityProvider' => new ActiveDataProvider([
                'query' => $query,
            ]),
        ]);
    }
}
