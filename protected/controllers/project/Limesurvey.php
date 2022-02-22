<?php

declare(strict_types=1);

namespace prime\controllers\project;

use prime\components\Controller;
use prime\models\ar\read\Project;
use yii\base\Action;
use yii\web\NotFoundHttpException;

class Limesurvey extends Action
{
    public function run(
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $project = Project::findOne(['id' => $id]);
        if (!isset($project)) {
            throw new NotFoundHttpException();
        }
        return $this->controller->render('limesurvey', [
            'project' => $project
        ]);
    }
}
