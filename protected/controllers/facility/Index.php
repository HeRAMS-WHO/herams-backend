<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use herams\common\domain\facility\FacilityRead;
use yii\base\Action;
use yii\data\ActiveDataProvider;

class Index extends Action
{
    public function run()
    {
        $query = FacilityRead::find();
        return $this->controller->render('index', [
            'facilityProvider' => new ActiveDataProvider([
                'query' => $query,
            ]),
        ]);
    }
}
