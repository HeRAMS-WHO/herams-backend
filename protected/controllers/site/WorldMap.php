<?php

namespace prime\controllers\site;

use yii\base\Action;
use yii\web\User;

class WorldMap extends Action
{
    public function run(User $user)
    {
        $this->controller->layout = 'css3-grid';
        return $this->controller->render('world-map');
    }
}
