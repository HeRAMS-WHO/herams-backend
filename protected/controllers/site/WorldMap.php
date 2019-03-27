<?php


namespace prime\controllers\site;


use prime\models\ar\Project;
use yii\base\Action;

class WorldMap extends Action
{

    public function run()
    {
        $this->controller->layout = 'css3-grid';
        return $this->controller->render('world-map', [
            'projects' => Project::find()->orderBy('title')->all()
        ]);
    }

}