<?php
declare(strict_types=1);

namespace prime\controllers\project;

use prime\models\ar\Project;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class ExternalDashboard extends Action
{

    public function run(
        Request $request,
        User $user,
        int $id
    ) {
        $this->controller->layout = 'css3-grid';
        $project = Project::findOne(['id'  => $id]);
        if (!isset($project) || null === $project->getOverride('dashboard')) {
            throw new NotFoundHttpException();
        }
        if (!$user->can(Permission::PERMISSION_READ, $project)) {
            throw new ForbiddenHttpException();
        }

        return $this->controller->render('external-dashboard', [
            'project' => $project
        ]);
    }
}
