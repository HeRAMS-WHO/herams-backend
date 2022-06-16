<?php


namespace prime\controllers\site;

use yii\base\Action;
use yii\web\User;

class WorldMap extends Action
{

    public function run(User $user)
    {
        $this->controller->layout = 'css3-grid';
        $search = new \prime\models\search\Project();
        $search->withFields = [];
        $dataProvider = $search->search([], $user);
        $dataProvider->setPagination(false);
        return $this->controller->render('world-map', [
            'projects' => $dataProvider
        ]);
    }
}
