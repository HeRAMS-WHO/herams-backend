<?php
declare(strict_types=1);

namespace prime\controllers\facility;

use prime\models\ar\Facility;
use yii\base\Action;

class Create extends Action
{

    public function run()
    {
        $facility = new Facility();
        return $this->controller->render('create', ['facility' => $facility]);
    }
}
