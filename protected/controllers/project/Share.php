<?php


namespace prime\controllers\project;


use prime\components\NotificationService;
use prime\models\ar\Project;
use prime\models\forms\Share as ShareForm;
use prime\models\permissions\Permission;
use SamIT\abac\AuthManager;
use yii\base\Action;
use yii\rbac\CheckAccessInterface;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Share extends Action
{


    public function run(
        Request $request,
        User $user,
        NotificationService $notificationService,
        AuthManager $abacManager,
        int $id
    ) {
        $project = Project::findOne(['id' => $id]);
        if (!isset($project)) {
            throw new NotFoundHttpException();
        }

        if (!$user->can(Permission::PERMISSION_SHARE, $project)) {
            throw new ForbiddenHttpException('You cannot share');
        }
        $model = new ShareForm(
            $project, $abacManager, $user->identity, [
            'permissions' => [
                Permission::PERMISSION_READ => 'Allow access to the project dashboard from the world map',
                Permission::PERMISSION_WRITE => 'Allows full access to all workspaces in this project as well as creating new ones or deleting existing ones',
                Permission::PERMISSION_ADMIN,
            ]
        ]);
        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->validate()) {
                $model->createRecords();
                $notificationService->success(\Yii::t('app',
                            "Project {modelName} has been shared with: {users}",
                            [
                                'modelName' => $project->title,
                                'users' => implode(', ', array_map(function($model){return $model->name;}, $model->getUsers()->all()))
                            ])
                );
                return $this->controller->refresh();
            }
        }

        return $this->controller->render('share', [
            'model' => $model,
            'project' => $project
        ]);
    }
}