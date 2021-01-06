<?php


namespace prime\controllers\project;

use prime\models\ar\Permission;
use prime\models\ar\Project;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\User;

class ImportDashboard extends Action
{
    public function run(
        Response $response,
        Request $request,
        User $user,
        int $id
    ) {
        $this->controller->layout = 'admin-screen';
        /** @var Project|null $project */
        $project = Project::find()->where(['id' => $id])->one();
        if (!isset($project)) {
            throw new NotFoundHttpException('Project not found');
        }
        if (!$user->can(Permission::PERMISSION_MANAGE_DASHBOARD, $project)) {
            throw new ForbiddenHttpException();
        }
        $model = new \prime\models\forms\ImportDashboard($project);

        if ($request->isPost
            && $model->load($request->bodyParams)
            && $model->validate()
        ) {
            $model->run();
            return $this->controller->redirect(['project/pages', 'id' => $project->id]);
        }


        return $this->controller->render('import', ['model' => $model, 'project' => $project]);
    }
}
